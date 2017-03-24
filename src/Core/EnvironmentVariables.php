<?php

namespace Uno\Core;


use Dotenv\Dotenv;

class EnvironmentVariables
{
    public function load($dir = __DIR__ . '/../../')
    {
        $dotenv = new Dotenv($dir);
        $dotenv->load();
    }
}