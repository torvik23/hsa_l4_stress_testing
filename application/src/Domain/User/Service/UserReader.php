<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\Exception\ValidationException;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserReaderRepository;

final class UserReader
{
    private UserReaderRepository $repository;

    /**
     * Constructor.
     *
     * @param UserReaderRepository $repository
     */
    public function __construct(UserReaderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a user by the given user id.
     *
     * @param int $userId The user id
     *
     * @throws ValidationException
     *
     * @return User The user data
     */
    public function getUserDetails(int $userId): User
    {
        // Input validation
        if (empty($userId)) {
            throw new ValidationException('User ID required');
        }

        $userRow = $this->repository->getById($userId);
        return $this->buildUser($userRow);
    }

    /**
     * Read a user by the given username.
     *
     * @param string $username The username
     *
     * @throws ValidationException
     *
     * @return User The user data
     */
    public function getUserDetailsByUsername(string $username): User
    {
        // Input validation
        if (empty($username)) {
            throw new ValidationException('Username is required');
        }

        $userRow = $this->repository->getByUsername($username);

        return $this->buildUser($userRow);
    }

    /**
     * Read all users.
     *
     * @throws ValidationException
     *
     * @return User[]
     */
    public function getUserList(): array
    {
        $userList = $this->repository->getList();
        $result = [];
        foreach ($userList as $userItem) {
            $result[] = $this->buildUser($userItem);
        }

        return $result;
    }

    /**
     * Build user instance from user data.
     *
     * @param array $userData
     *
     * @return User
     */
    private function buildUser(array $userData): User
    {
        $user = new User();
        $user->id = (int) $userData['id'];
        $user->username = (string) $userData['username'];
        $user->firstName = (string) $userData['first_name'];
        $user->lastName = (string) $userData['last_name'];
        $user->email = (string) $userData['email'];

        return $user;
    }
}
