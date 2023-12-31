<?php

use Slim\Flash\Messages;
use App\Exceptions\Handler;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface as Request;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$container = new DI\Container();

Slim\Factory\AppFactory::setContainer($container);

$app = Slim\Factory\AppFactory::create();

$container->set('settings', function () {
    return [
        'displayErrorDetails' => getenv('APP_DEBUG') === 'true',

        'app' => [
            'name' => getenv('APP_NAME')
        ],

        'views' => [
            'cache' => getenv('VIEW_CACHE_DISABLED') === 'true' ? false : __DIR__ . '/../storage/views'
        ]
    ];
});

$twig = new Slim\Views\Twig(__DIR__ . '/../resources/views', [
    'cache' => $container->get('settings')['views']['cache']
]);

$twigMiddleware = new Slim\Views\TwigMiddleware(
    $twig,
    $container,
    $app->getRouteCollector()->getRouteParser()
);

$app->add($twigMiddleware);

$container->set('flash', function () {
   return new Messages();
});

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setDefaultErrorHandler(
    new Handler(
        $app->getResponseFactory(),
        $container->get('flash')
    )
);

require_once __DIR__ . '/../routes/web.php';
