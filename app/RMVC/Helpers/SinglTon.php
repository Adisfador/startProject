<?php

namespace App\RMVC\Helpers;


trait SinglTon
{

    private static $instanse;

    public static function insatnce()
    {
        return self::$instanse == NULL ? self::$instanse = new self() : self::$instanse;
    }

    function __construct()
    {
        
    }

    function __clone()
    {
        
    }

    function __wakeup()
    {
        
    }
}