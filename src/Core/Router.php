<?php

namespace Uno\Core;

use League\Route\RouteCollection;

class Router
{
    public function process($app, $routesPath = null)
    {
        $route = new RouteCollection($app);

        require_once (is_null($routesPath) ? routes_path('web.php') : $routesPath);

        $response = $route->dispatch($app->make('request'), $app->make('response'));

        $app->make('emitter')->emit($response);
    }
}