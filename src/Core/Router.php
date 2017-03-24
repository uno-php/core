<?php

namespace Uno\Core;


use AltoRouter;

class Router
{
    public function process($routes = null, $namespace = "App\\Controllers\\")
    {
        $router = new AltoRouter();

        $routes = require_once is_null($routes) ? routes_path('web.php') : null;

        $router->addRoutes($routes);

        $match = $router->match();

        $target = explode("@", $namespace . $match['target'] );

        // call closure or throw 404 status
        if( $match && is_callable( $target ) ) {
//            return call_user_func_array( $target, $match['params'] );
            return (new $target[0]())->{$target[1]}($match['params']);
        } else {
            // no route was matched
            return pageNotFound();
        }
    }
}