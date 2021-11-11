<?php

declare(strict_types=1);

namespace App\Application\Action;

use App\Domain\User\Service\UserCreator;
use App\Domain\User\Service\UserReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class StressAction
{
    /**
     * @var UserCreator
     */
    private UserCreator $userCreator;

    /**
     * @var UserReader
     */
    private UserReader $userReader;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cacheClient;

    /**
     * Constructor.
     *
     * @param UserCreator $userCreator
     * @param UserReader $userReader
     * @param CacheInterface $cacheClient
     */
    public function __construct(
        UserCreator $userCreator,
        UserReader $userReader,
        CacheInterface $cacheClient
    ) {
        $this->userCreator = $userCreator;
        $this->userReader = $userReader;
        $this->cacheClient = $cacheClient;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $cacheStatus = (int) $request->getHeaderLine('Cache-Status') ?? 0;
            $data = (array) $request->getParsedBody();
            //if $cacheStatus === 1 then we use Stampede Prevention cache strategy
            if ($cacheStatus === 1) {
                $userDetails = $this->cacheClient->get(
                    $data['username'] ?? '',
                    function (ItemInterface $item) use ($data) {
                        $item->expiresAfter(3000);
                        $this->userCreator->createUser($data);
                        return $this->userReader->getUserDetailsByUsername($data['username'] ?? '');
                    },
                    1.0
                );
            } else {
                $this->userCreator->createUser($data);
                $userDetails = $this->userReader->getUserDetailsByUsername($data['username'] ?? '');
            }
            $result = ['user_details' => $userDetails];
            $status = 201;
        } catch (\Exception $exception) {
            $result = [
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode()
            ];
            $status = 500;
        }
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
