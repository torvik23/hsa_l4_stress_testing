<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use DomainException;
use PDO;

final class UserReaderRepository
{
    /**
     * The database connection.
     *
     * @var PDO
     */
    private PDO $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get user by the given user id.
     *
     * @param int $userId The user id
     *
     * @throws DomainException
     *
     * @return array The user row
     */
    public function getById(int $userId): array
    {
        $sql = "SELECT id, username, first_name, last_name, email FROM users WHERE id = :id;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $userId]);

        $row = $statement->fetch();

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $userId));
        }

        return $row;
    }

    /**
     * Get user by the given username.
     *
     * @param string $username The username
     *
     * @throws DomainException
     *
     * @return array The user row
     */
    public function getByUsername(string $username): array
    {
        $sql = "SELECT id, username, first_name, last_name, email FROM users WHERE username = :username;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['username' => $username]);

        $row = $statement->fetch();

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $username));
        }

        return $row;
    }

    /**
     * Get user list.
     *
     * @throws DomainException
     *
     * @return array
     */
    public function getList(): array
    {
        $sql = "SELECT id, username, first_name, last_name, email FROM users;";
        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $data = $statement->fetchAll();
        if (!$data) {
            throw new DomainException('Users are not found');
        }

        return $data;
    }
}
