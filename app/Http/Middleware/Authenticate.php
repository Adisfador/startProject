<?php

namespace App\Http\Middleware;

use App\RMVC\Auth\Auth;
use App\RMVC\Helpers\Request;
use App\RMVC\Route\Route;

class Authenticate
{
    private $auth;

    public function __construct()
    {
        $this->auth = new Auth;
        $this->checkAuthorization();


        if ((new Request)->server['REQUEST_URI'] == "/admin/logout") {
            $this->auth->unAuthorize();
        }
    }
    public function checkAuthorization()
    {
        print_r($this->auth->hashUser());
        if ($this->auth->hashUser() !== null) {
            $this->auth->authorize($this->auth->hashUser());
        }
        if (!$this->auth->authorized()) {
            Route::redirect('/auth/login');
            die();
        }
    }
}
