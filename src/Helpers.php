<?php

use Uno\Core\TemplateEngine;

if(!function_exists('dd')) {
    function dd(...$args) {
        var_dump(...$args);die();
    }
}

if(!function_exists('env')) {
    function env($value, $default = '') {
        return (getenv($value)) ? getenv($value) : $default;
    }
}

if(!function_exists('config')) {
    function config($value, $default = null) {
        $value = explode('.', $value);

        $data = (file_exists(config_path($value[0]) .'.php'))
            ? require config_path($value[0]) .'.php'
            : null;

        if((count($value) > 1) && !is_null($data)){

            $data = $data[$value[1]];

            if(isset($value[2])&& !is_null($data)) {
                isset($data[$value[2]]) ? $data[$value[2]] : null;
            }

            if(isset($value[3])&& !is_null($data)) {
                $data = isset($data[$value[3]]) ? $data[$value[3]] : null;
            }
        }
        return is_null($data) ? (!empty($default) ? $default : $data) : $data;
    }
}

if(!function_exists('view')) {
    function view($name, $params = [], $path = "views/", $ext = '.phtml') {

        $templateEngine = new TemplateEngine($path);

        echo $templateEngine->render($name . $ext,  $params);
    }
}


if(!function_exists('public_path')) {
    function public_path($name = '') {
        return __DIR__ . "/../public/" . $name;
    }
}

if(!function_exists('resources_path')) {
    function resources_path($name = '') {
        return __DIR__ . "/../resources/" . $name;
    }
}

if(!function_exists('app_path')) {
    function app_path($name = '') {
        return __DIR__ . "/../app/" . $name;
    }
}

if(!function_exists('storage_path')) {
    function storage_path($name = '') {
        return __DIR__ . "/../storage/" . $name;
    }
}

if(!function_exists('config_path')) {
    function config_path($name = '') {
        return __DIR__ . "/../config/" . $name;
    }
}

if(!function_exists('routes_path')) {
    function routes_path($name = '') {
        return __DIR__ . "/../routes/" . $name;
    }
}


if(!function_exists('asset')) {
    function asset($string){
        return config('app.url') . '/' . $string;
    }
}

if(!function_exists('url')) {
    function url($string = ''){
        return asset($string);
    }
}