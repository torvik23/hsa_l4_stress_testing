<?php

declare(strict_types=1);

namespace App\Application\Action;

use Faker\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class FakerAction
{
    private Factory $fakerFactory;

    private const URL_FORMAT = "http://localhost/stress POST {\"username\":\"%s\",\"first_name\":\"%s\",\"last_name\":\"%s\",\"email\":\"%s\"}\n";

    /**
     * @param Factory $fakerFactory
     */
    public function __construct(Factory $fakerFactory)
    {
        $this->fakerFactory = $fakerFactory;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Path to the yaml file
        $file = __DIR__ . '/../../../resources/urls.txt';

        $faker = $this->fakerFactory::create();
        $content = sprintf(
            self::URL_FORMAT,
            strtolower($faker->userName),
            $faker->firstName,
            $faker->lastName,
            $faker->email,
        );
        file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

        $response->getBody()->write((string)json_encode(['urls_was_generated' => true]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
