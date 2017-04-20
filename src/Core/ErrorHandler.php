<?php

namespace Uno\Core;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Run;
use Whoops\Util\Misc;

class ErrorHandler
{
    public function handle()
    {
//        if(!config('app.debug'))  $this->nullHandler();

        $this->prettyHandler();
    }

    /**
     * @return mixed
     */
    private function nullHandler()
    {
        return view('errors/app', [], 500);
    }

    private function prettyHandler()
    {
        $run = new Run;
        $handler = new PrettyPageHandler;

        // Add some custom tables with relevant info about your application,
        // that could prove useful in the error page:
        $handler->addDataTable(config('app.name') . ' Details', [
            "Uno PHP Version" => app()->version(),
        ]);

        // Set the title of the error page:
        $handler->setPageTitle("Whoops! There was a problem.");

        $run->pushHandler($handler);

        // Add a special handler to deal with AJAX requests with an
        // equally-informative JSON response. Since this handler is
        // first in the stack, it will be executed before the error
        // page handler, and will have a chance to decide if anything
        // needs to be done.
        if (Misc::isAjaxRequest()) {
            $run->pushHandler(new JsonResponseHandler);
        }

        // Register the handler with PHP, and you're set!
        $run->register();
    }
}