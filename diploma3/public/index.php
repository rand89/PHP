<?php

if(!session_id()) session_start();

require "../vendor/autoload.php";

use DI\ContainerBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use Aura\SqlQuery\QueryFactory;

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions([
    Engine::class => function() {
        return new Engine('../app/views');
    },

    PDO::class => function() {
        return new PDO("mysql:host=localhost; dbname=project3", 'root', '');
    },

    Auth::class => function($container) {
        return new Auth($container->get('PDO'));
    },

    QueryFactory::class =>function() {
        return new QueryFactory('mysql');
    }

]);

$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\HomeController', 'index']);
    $r->addRoute('POST', '/', ['App\controllers\HomeController', 'register']);
    $r->addRoute('GET', '/login', ['App\controllers\HomeController', 'pageLogin']);
    $r->addRoute('POST', '/login', ['App\controllers\HomeController', 'login']);
    $r->addRoute('GET', '/logout', ['App\controllers\HomeController', 'logout']);
    $r->addRoute('GET', '/users', ['App\controllers\HomeController', 'pageUsers']);
    $r->addRoute('GET', '/create', ['App\controllers\HomeController', 'pageCreateUser']);
    $r->addRoute('POST', '/create', ['App\controllers\HomeController', 'createUser']);
    $r->addRoute('GET', '/profile/{id:\d+}', ['App\controllers\HomeController', 'pageProfile']);
    $r->addRoute('GET', '/edit/{id:\d+}', ['App\controllers\HomeController', 'pageEdit']);
    $r->addRoute('POST', '/edit', ['App\controllers\HomeController', 'edit']);
    $r->addRoute('GET', '/status/{id:\d+}', ['App\controllers\HomeController', 'pageStatus']);
    $r->addRoute('POST', '/status', ['App\controllers\HomeController', 'status']);
    $r->addRoute('GET', '/photo/{id:\d+}', ['App\controllers\HomeController', 'pagePhoto']);
    $r->addRoute('POST', '/photo', ['App\controllers\HomeController', 'photo']);
    $r->addRoute('GET', '/security/{id:\d+}', ['App\controllers\HomeController', 'pageSecurity']);
    $r->addRoute('POST', '/security', ['App\controllers\HomeController', 'security']);
    $r->addRoute('GET', '/delete/{id:\d+}', ['App\controllers\HomeController', 'deleteUser']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "Error 404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "Error 405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($handler, $vars);
        break;
}

function redirectTo($location = null) {
    if($location) {
        header("Location: ".$location);
        exit;
    }
}

?>