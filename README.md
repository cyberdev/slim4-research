# slim4-research #
Slim Framework Research

**Create project skeleton**
```batch
composer create-project slim/slim-skeleton [my-app-name]
```

**Middleware using separate class**

*src/Application/Middleware/ExampleBeforeMiddleware*
```php
<?php
namespace App\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ExampleBeforeMiddleware
{
   /**
   * Example middleware invokable class
   *
   * @param  ServerRequest  $request PSR-7 request
   * @param  RequestHandler $handler PSR-15 request handler
   *
   * @return Response
   */
   public function __invoke(Request $request, RequestHandler $handler): Response
   {
      $response = $handler->handle($request);
      $existingContent = (string) $response->getBody();
   
      $response = new Response();
      $response->getBody()->write('BEFORE ' . $existingContent);
   
      return $response;
   }
}
```

*src/Application/Middleware/ExampleAfterMiddleware*
```php
<?php
namespace App\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ExampleAfterMiddleware
{
   /**
   * Example middleware invokable class
   *
   * @param  ServerRequest  $request PSR-7 request
   * @param  RequestHandler $handler PSR-15 request handler
   *
   * @return Response
   */
   public function __invoke(Request $request, RequestHandler $handler): Response
   {
      $response = $handler->handle($request);
      $response->getBody()->write(' AFTER');
      return $response;
   }
}
```

*public/index.php*
```php
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
```