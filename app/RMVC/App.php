<?php

namespace App\RMVC;

use App\RMVC\Helpers\Request;
use App\RMVC\Route\Route;
use App\RMVC\Route\RouteDispatcher;


class App
{
    public static function run()
    {

        $requestMethod=ucfirst(strtolower((new Request)->server["REQUEST_METHOD"])) ;
        $methodName="getRoutes".$requestMethod;
        foreach (Route::$methodName() as $RouteConfiguration) {
            $routeDispatcher=new RouteDispatcher($RouteConfiguration);
            $routeDispatcher->process();
        }
    }
}
