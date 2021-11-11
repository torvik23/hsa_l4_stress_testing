<?php

declare(strict_types=1);

namespace App\Application\Action;

use App\Domain\User\Service\UserReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserReadAction
{
    /**
     * @var UserReader
     */
    private UserReader $userReader;

    /**
     * Constructor.
     *
     * @param UserReader $userReader
     */
    public function __construct(UserReader $userReader)
    {
        $this->userReader = $userReader;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        $userId = (int) ($args['id'] ?? 0);
        try {
            $user = $this->userReader->getUserDetails($userId);
            $result = [
                'user_id' => $user->id,
                'username' => $user->username,
                'first_name' => $user->firstName,
                'last_name' => $user->lastName,
                'email' => $user->email,
            ];
        } catch (\Exception $exception) {
            $result = [
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode()
            ];
        }
        $response->getBody()->write((string) json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
