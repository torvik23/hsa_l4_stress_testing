<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

final class User
{
    /**
     * @var int
     */
    public int $id;

    /** @var string */
    public string $username;

    /** @var string */
    public string $firstName;

    /** @var string */
    public string $lastName;

    /** @var string */
    public string $email;
}
