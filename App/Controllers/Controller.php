<?php

namespace App\Controllers;

class Controller
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if( $this->container->{$property} )
        {
            return $this->container->{$property};
        }
    }
}
