<?php


namespace Uno\Contracts;


interface DBEngine
{
    public function connect($config);
}