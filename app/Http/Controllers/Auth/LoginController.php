<?php

namespace App\Http\Controllers\Auth;

use App\RMVC\Route\Route;
use App\RMVC\View\View;
use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use App\RMVC\Helpers\Request;
use App\RMVC\Auth\Auth;

class LoginController extends Controller
{
    protected $auth;
    public function __construct()
    {
        $this->auth = new Auth;

        if($this->auth->hashUser() !==null){
            $this->auth->authorize($this->auth->hashUser());
        }
        if($this->auth->authorized()){
            Route::redirect('/admin');
        }
    }


    public function form()
    {
        return View::view("auth.login");
    }

    public function authAdmin()
    {
        $params = (new Request)->post;



        $query = (new User)->query()->where("email", $params["email"])->andWhere("password", md5($params["password"]))->limit(1)->select();
        if (!empty($query)) {
            $user = $query[0];

            if ($user['role'] == 'admin') {
                $hash = md5($user['email'] . $user['password'] . $this->auth->salt());
                (new User)->query()->update(["hash" => $hash], ["id", $user['id']]);
                $this->auth->authorize($hash);
            }
        } else {
            Route::redirect('/auth/login');
            die();
        }

       

        Route::redirect('/admin');

    }
    
    public function logout(){
        Route::redirect('/auth/login');
        die();
    }
}
