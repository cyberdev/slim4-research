<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

/**
 * Example middleware closure
 *
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-15 request handler
 *
 * @return Response
 */
$beforeMiddleware = function (RequestInterface $request, RequestHandlerInterface $handler) {
   $response = $handler->handle($request);
   $existingContent = (string) $response->getBody();

   $response = new Response();
   $response->getBody()->write('BEFORE ' . $existingContent);

   return $response;
};

$afterMiddleware = function (RequestInterface $request, RequestHandlerInterface $handler) {
   $response = $handler->handle($request);
   $response->getBody()->write(' AFTER');
   return $response;
};

$app->add($beforeMiddleware);
$app->add($afterMiddleware);

$app->addErrorMiddleware(true, true, false);

$app->get('/hello/{name}', function (RequestInterface $request, ResponseInterface $response, $args) {
   $name = $args['name'];
   $response->getBody()->write("Hello, $name");
   return $response;
});

$app->run();