<?php
/**
 * Created by PhpStorm.
 * User: Zchu
 * Date: 2018/4/28
 * Time: 8:43
 */
use DI\ContainerBuilder;
use App\Controller\HelloController;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use function DI\autowire;
require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();

$classes = [
    HelloController::class => \DI\create(HelloController::class)->constructor(\DI\get('Request')),
    'Request' => function(){
        return new \Zend\Diactoros\Response();
    }
];

$builder->addDefinitions($classes);
try{
    $container = $builder->build();

    $routes = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
        $r->get('/hello',HelloController::class);
    });

    $requestHandler = new \Relay\Relay([
        new FastRoute($routes),
        new RequestHandler($container),
    ]);

    $response = $requestHandler->handle(\Zend\Diactoros\ServerRequestFactory::fromGlobals());

    $emeitter = new \Zend\Diactoros\Response\SapiEmitter();
    $emeitter->emit($response);

} catch (\Exception $exception)
{
    echo $exception->getMessage();
}
