<?php

namespace Uno\Core;

use Dotenv\Dotenv;

class EnvironmentVariables
{
    public function load($dir = null)
    {
        (new Dotenv(!is_null($dir) ? $dir : base_path()))->load();
    }
}