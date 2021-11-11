<?php

declare(strict_types=1);

namespace App\Application\Action;

use App\Domain\User\Service\UserCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class UserCreateAction
{
    /**
     * @var UserCreator
     */
    private UserCreator $userCreator;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cacheClient;

    /**
     * Constructor.
     *
     * @param UserCreator $userCreator
     * @param CacheInterface $cacheClient
     */
    public function __construct(UserCreator $userCreator, CacheInterface $cacheClient)
    {
        $this->userCreator = $userCreator;
        $this->cacheClient = $cacheClient;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $cacheStatus = (int) $request->getHeaderLine('Cache-Status');
            $data = (array) $request->getParsedBody();
            $username = $data['username'] ?? '';
            //if $cacheStatus === 1 then we use Stampede Prevention cache strategy
            if ($cacheStatus === 1) {
                $userId = $this->cacheClient->get($username, function (ItemInterface $item) use ($data) {
                    $item->expiresAfter(10);
                    return $this->userCreator->createUser($data);
                }, 1.0);
            } else {
                $userId = $this->userCreator->createUser($data);
            }
            $result = ['user_id' => $userId];
        } catch (\Exception $exception) {
            $result = [
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode()
            ];
        }
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
