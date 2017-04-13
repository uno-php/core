<?php

namespace Uno\Core;

use Uno\Database\DB;
use Uno\Mail\Mail;
use Zend\Diactoros\Response;
use Illuminate\Container\Container;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
//use Psr\Http\Message\ResponseInterface;
//use Psr\Http\Message\ServerRequestInterface;

class App extends Container
{
    /**
     * The Uno framework version.
     *
     * @var string
     */
    const VERSION = '0.0.1';

    public $basePath;

    /**
     * Create a new Illuminate application instance.
     *
     * @param  string|null $basePath
     */
    public function __construct($basePath = null)
    {
//        parent::__construct();

        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();

        $this->loadDefaultBindings();

//        $this->registerBaseServiceProviders();

//        $this->registerCoreContainerAliases();

        parent::__construct();

    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
    }

    public function loadDefaultBindings()
    {
        // Bind a "mailer" class to the container
        // Use a callback to set additional settings
        $this->bind('mailer', function ($container) {
            $mailer = new Mail;

            $mailer->username = config('mail.username', 'username');
            $mailer->password = config('mail.password', 'password');
            $mailer->from = config('mail.from', 'foo@bar.com');

            return $mailer;
        });

        // Bind a shared "database" class to the container
        // Use a callback to set additional settings
        $this->singleton('database', function ($container) {
            return new DB();
        });

        // Bind Template Engine
        $this->singleton('template', function ($container) {
            return new TemplateEngine();
        });
    }

    protected function setupDefaultBindings() {

        $this->bind('response', Response::class);

        $this->bind('emitter', SapiEmitter::class);

        $this->bind('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });
    }

    public function run($routesPath = null)
    {
        (new ErrorHandler)->handle();

        (new EnvironmentVariables)->load($this->basePath);

        (new Router())->process($this, $routesPath);
    }

    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }
}
