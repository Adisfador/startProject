<?php

namespace App\RMVC\Route;

class Route {
    private static array $routesGet=[];
    private static array $routesPost=[];

    public static function getRoutesGet(){
        return self::$routesGet;
    }
    public static function getRoutesPost(){
        return self::$routesPost;
    }

    public static function get(string $route, array $controller):RouteConfiguration
    {
        $RouteConfiguration=new RouteConfiguration( $route, $controller[0], $controller[1]);
        self::$routesGet[]= $RouteConfiguration;
        return  $RouteConfiguration;
    }

    public static function post(string $route, array $controller):RouteConfiguration
    {
        $RouteConfiguration=new RouteConfiguration( $route, $controller[0], $controller[1]);
        self::$routesPost[]= $RouteConfiguration;
        return  $RouteConfiguration;
    }

    public static function redirect(string $url)
    {
       header('Location:'.$url);
    }

}