<?php

namespace Uno\Core;

use Twig_Environment;
use Twig_Lexer;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

class TemplateEngine
{
    private $twig;

    public function __construct($viewsPath = 'views', $cachePath = 'cache/twig/'){
        $loader = new Twig_Loader_Filesystem(resources_path($viewsPath));

        $this->twig = new Twig_Environment($loader, array(
            'cache' => storage_path($cachePath),
            'debug' => config('app.debug'),
            'strict_variables' => true
        ));

        $this->createFunctions();

        $this->createLexers();
    }

    private function createFunctions()
    {
        $urlFunction = new Twig_SimpleFunction('url', function ($name) {
            return url($name);
        });

        $assetFunction = new Twig_SimpleFunction('asset', function ($name) {
            return asset($name);
        });

        $configFunction = new Twig_SimpleFunction('config', function ($name, $default = '') {
            return config($name, $default);
        });

        $mixFunction = new Twig_SimpleFunction('mix', function ($path) {
            return mix($path);
        });

        $this->twig->addFunction($assetFunction);
        $this->twig->addFunction($urlFunction);
        $this->twig->addFunction($configFunction);
        $this->twig->addFunction($mixFunction);
    }

    public function render($file, $params)
    {
        return $this->twig->render($file,  $params);
    }

    private function createLexers()
    {
        $lexer = new Twig_Lexer($this->twig, array(
            'tag_comment' => ["<!--", "-->"],
            'tag_variable' => ['{{', '}}'],
            'interpolation' => ['#{', '}']
        ));

        $this->twig->setLexer($lexer);
    }
}