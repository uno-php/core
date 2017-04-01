<?php


namespace Uno\Core;


use Illuminate\Container\Container as IlluminateContainer;
use Uno\Database\DB;
use Uno\Mail\Mailer;

class Container extends IlluminateContainer
{

    public function loadDefaultBindings()
    {
        // Bind Template Engine
        $this->bind('template', TemplateEngine::class);

        // Bind a "mailer" class to the container
        // Use a callback to set additional settings
        $this->bind('mailer', function ($container) {
            $mailer = new Mailer;

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
    }

}