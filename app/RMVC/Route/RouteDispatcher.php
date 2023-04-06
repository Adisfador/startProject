<?php

namespace App\RMVC\Route;
use App\RMVC\Helpers\Request;


class RouteDispatcher
{

    private string $requestUri = "/";
    private RouteConfiguration $RouteConfiguration;
    private array $paramMap = [];
    private array $paramRequestMap=[];

    public function __construct(RouteConfiguration $RouteConfiguration)
    {
        $this->RouteConfiguration = $RouteConfiguration;
    }

    public function process()
    {

        $this->saveRequestUri();

        $this->setParamMap();

        $this->saveRedexRequest();
        $this->run();


    }


    private function saveRequestUri()
    {
        if ((new Request)->server["REQUEST_URI"] !== "/") {
            $this->requestUri = $this->clean((new Request)->server["REQUEST_URI"]);
            $this->RouteConfiguration->route = $this->clean($this->RouteConfiguration->route);
        }
    }

    private function clean(string $str)
    {

        return preg_replace('/(^\/)|(\/$)/', '', $str);
    }

    private function setParamMap()
    {
        $routeArray = explode("/", $this->RouteConfiguration->route);

        foreach ($routeArray as $paramKey => $param) {
            if (preg_match("/{.*}/", $param)) {
                $this->paramMap[$paramKey] = preg_replace('/(^{)|(}$)/', "", $param);
            }
        }
    }

    private function saveRedexRequest()
    {
        $RequestUriArr = explode("/", $this->requestUri);

        foreach ($this->paramMap as $paramKey => $param) {
            if (!isset($RequestUriArr[$paramKey])) {
                return;
            }
            $this->paramRequestMap[$param]=$RequestUriArr[$paramKey];
            $RequestUriArr[$paramKey] = '{.*}';
            
        }
       
        $this->requestUri = implode('/', $RequestUriArr);
    }


    private function prepareRedex()
    {
        $this->requestUri = str_replace("/", "\/", $this->requestUri);
    }

    private function run()
    {
        $this->prepareRedex();

        if (preg_match("/^$this->requestUri$/", $this->RouteConfiguration->route)) {
            $this->render();
        }
    }

    private function render()
    {
        $className = $this->RouteConfiguration->controller;
        $action = $this->RouteConfiguration->action;


        if(!empty($this->RouteConfiguration->middleware)){
            $middlewareName= 'App\Http\Middleware'.'\\'.ucwords($this->RouteConfiguration->middleware);
            $middleware=new $middlewareName;
        } 



        print_r((new $className)->$action(...$this->paramRequestMap));
        die();
    }
}
