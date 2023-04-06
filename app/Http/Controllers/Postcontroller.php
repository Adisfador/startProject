<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;
use App\Models\UserModel;

class Postcontroller extends Controller
{
    public function index()
    {
        $users = (new UserModel)->query()->select();
        return View::view("post.index", compact("users"));
        // return View::view("post.index");
    }

    public function show($post)
    {


        return View::view("post.show", compact("post"));
    }

    public function store()
    {

        $_SESSION["message"] = $_POST["text"];
        Route::redirect('/post');
        die();
    }

    public function main()
    {

        return View::view("admin");
        die();
    }
}
