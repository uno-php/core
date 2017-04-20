<?php

namespace Uno\Core;

use League\Route\RouteCollection;

class Router
{
    public function process($routesPath = null)
    {
        $app = app();

        $route = new RouteCollection($app);

        require_once (is_null($routesPath) ? routes_path('web.php') : $routesPath);

        $response = $route->dispatch($app->get('request'), $app->get('response'));

        $app->get('emitter')->emit($response);
    }
}