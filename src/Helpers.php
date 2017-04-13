<?php

use Uno\Core\App;
use Illuminate\Support\Collection;
use Uno\Core\TemplateEngine;


if (! function_exists('app')) {
    function app($abstract = null)
    {
        if (is_null($abstract)) {
            return App::getInstance();
        }

        return App::getInstance()->make($abstract);
    }
}

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

        return is_null($data)
            ? (!empty($default) ? $default : ((!is_bool($data)) ? $data : boolval($data)))
            : ((!is_bool($data)) ? $data : boolval($data));
    }
}

if(!function_exists('app')) {
    function app($name, $params = [], $path = "views/", $ext = '.html')
    {
        $template = app('template')->make($path);
        echo $template->render($name . $ext,  $params);
//        $templateEngine = new TemplateEngine($path);

//        echo $templateEngine->render($name . $ext,  $params);
    }
}

if(!function_exists('view')) {
    function view($name, $params = [], $path = "views/", $ext = '.html')
    {
//        $template = app('template')->make($path);
//        echo $template->render($name . $ext,  $params);
        $templateEngine = new TemplateEngine($path);

        echo $templateEngine->render($name . $ext,  $params);
    }
}


if(!function_exists('public_path')) {
    function public_path($name = '') {
        return base_path("public/" . $name);
    }
}

if(!function_exists('resources_path')) {
    function resources_path($name = '') {
        return base_path("resources/" . $name);
    }
}

if(!function_exists('app_path')) {
    function app_path($name = '') {
        return base_path("app/" . $name);
    }
}

if(!function_exists('storage_path')) {
    function storage_path($name = '') {
        return base_path("storage/" . $name);
    }
}

if(!function_exists('config_path')) {
    function config_path($name = '') {
        return base_path("config/" . $name);
    }
}

if(!function_exists('routes_path')) {
    function routes_path($name = '') {
        return base_path("routes/" . $name);
    }
}

if(!function_exists('base_path')) {
    function base_path($name = '', $basePath = __DIR__ . "/../../../framework/") {
        return $basePath. $name;
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

if(!function_exists('starts_with')) {
    function starts_with($haystack, $needle)
    {
        return (substr($haystack, 0, strlen($needle)) === $needle);
    }
}

if(!function_exists('ends_with')) {
    function ends_with($haystack, $needle)
    {
        $length = strlen($needle);

        return ($length == 0) ? true : (substr($haystack, - $length) === $needle);
    }
}

if(!function_exists('mix')) {
    function mix($path, $manifest = false, $shouldHotReload = false, $port = '8080')
    {
        if (! $manifest) static $manifest;
        if (! $shouldHotReload) static $shouldHotReload;
        if (! $manifest) {
            $manifestPath = public_path('mix-manifest.json');
            $shouldHotReload = file_exists(public_path('hot'));
            if (! file_exists($manifestPath)) {
                throw new Exception(
                    'The Uno PHP Mix manifest file does not exist. ' .
                    'Please run "npm run dev" and try again.'
                );
            }
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }
        if (! starts_with($path, '/')) $path = "/{$path}";
        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unknown Uno PHP Mix file path: {$path}. Please check your requested " .
                "webpack.mix.js output path, and try again."
            );
        }
        return $shouldHotReload
            ? "http://localhost:{$port}{$manifest[$path]}"
            : url(substr($manifest[$path],1));
    }
}

if (! function_exists('collect')) {
    function collect($value = null)
    {
        return new Collection($value);
    }
}

if (! function_exists('with')) {
    function with($object)
    {
        return $object;
    }
}

if (! function_exists('tap')) {
    function tap($value, $callback)
    {
        $callback($value);

        return $value;
    }
}

if (! function_exists('object_get')) {
    function object_get($object, $key, $default = null)
    {
        if (is_null($key) || trim($key) == '') {
            return $object;
        }

        foreach (explode('.', $key) as $segment) {
            if (! is_object($object) || ! isset($object->{$segment})) {
                return value($default);
            }

            $object = $object->{$segment};
        }

        return $object;
    }
}

if (! function_exists('class_basename')) {
    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}

if (! function_exists('array_wrap')) {
    function array_wrap($value)
    {
        return ! is_array($value) ? [$value] : $value;
    }
}