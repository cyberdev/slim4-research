<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use App\Application\Middleware\ExampleAfterMiddleware;
use App\Application\Middleware\ExampleBeforeMiddleware;


require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->add(ExampleAfterMiddleware::class);
$app->add(ExampleBeforeMiddleware::class);

$app->addErrorMiddleware(true, true, false);

$app->get('/hello/{name}', function (RequestInterface $request, ResponseInterface $response, $args) {
   $name = $args['name'];
   $response->getBody()->write("Hello, $name");
   return $response;
});

$app->run();