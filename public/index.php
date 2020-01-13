<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
   $name = $args['name'];
   $response->getBody()->write("Hello, $name");
   return $response;
});

$app->run();