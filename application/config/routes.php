<?php

declare(strict_types=1);

use App\Application\Action\FakerAction;
use App\Application\Action\StressAction;
use App\Application\Action\UserCreateAction;
use App\Application\Action\UserReadAction;
use Slim\App;

return function (App $app) {
    $app->get('/', FakerAction::class)->setName('home');
    $app->post('/stress', StressAction::class);
    $app->get('/user', UserReadAction::class);
    $app->post('/user', UserCreateAction::class);
};