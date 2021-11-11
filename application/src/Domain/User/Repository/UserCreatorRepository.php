<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use PDO;

final class UserCreatorRepository
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
     * Create user row.
     *
     * @param array $data
     *
     * @return int
     */
    public function create(array $data): int
    {
        $row = [
            'username' => $data['username'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ];

        $sql = "INSERT INTO users (username, first_name, last_name, email)
                VALUES (:username, :first_name, :last_name, :email) 
                ON DUPLICATE KEY UPDATE
                    first_name=:first_name,
                    last_name=:last_name,
                    email=:email;";

        $this->connection->prepare($sql)->execute($row);

        return (int) $this->connection->lastInsertId();
    }
}
