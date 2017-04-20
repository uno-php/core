<?php

namespace Uno\Core;


use Uno\Mail\Mail;
use Uno\Database\DB;
use Zend\Diactoros\Response;
use League\Container\Container;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;

class App extends Container
{
    /**
     * The Uno framework version.
     *
     * @var string
     */
    const VERSION = '0.0.1';

    /**
     * The current globally available container (if any).
     *
     * @var static
     */
    protected static $instance;

    public $basePath;

    /**
     * Create a new Illuminate application instance.
     *
     * @param  string|null $basePath
     */
    public function __construct($basePath = null)
    {
        parent::__construct();

        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();

        $this->setupRouteBindings();

        $this->loadDefaultBindings();
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

        $this->share('app', $this);

        $this->share(Container::class, $this);
    }

    public function loadDefaultBindings()
    {
        // Bind Template Engine
        $this->share('template', TemplateEngine::class);

        // Bind a shared "database" class to the container
        // Use a callback to set additional settings
        $this->share('database', DB::class);

        // Bind a "mailer" class to the container
        // Use a callback to set additional settings
        $this->share('mailer', function ($container) {
            $mailer = new Mail;

            $mailer->username = config('mail.username', 'username');
            $mailer->password = config('mail.password', 'password');
            $mailer->from = config('mail.from', 'foo@bar.com');

            return $mailer;
        });
    }

    protected function setupRouteBindings()
    {
        $this->share('response', Response::class);

        $this->share('emitter', SapiEmitter::class);

        $this->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });
    }

    public function run($routes = null)
    {
        (new ErrorHandler)->handle();

        (new EnvironmentVariables)->load($this->basePath);

        return (new Router)->process($routes);
    }

    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function make($id)
    {
        return $this->get($id);
    }

    /**
     * Set the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Set the shared instance of the container.
     *
     * @return static
     */
    public static function setInstance($container = null)
    {
        return static::$instance = $container;
    }
}
