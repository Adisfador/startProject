<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Postcontroller;
use App\RMVC\Route\Route;

Route::get('/post/{post}',[Postcontroller::class,"show"]);
Route::get('/post',[Postcontroller::class,"index"])->name("post.index");
Route::post('/post',[Postcontroller::class,"store"]);





Route::get('/auth/login',[LoginController::class,"form"]);
Route::post('/auth/admin',[LoginController::class,"authAdmin"]);
Route::get('/admin/logout',[LoginController::class,"logout"])->middleware("Authenticate");

Route::get('/admin',[Postcontroller::class,"main"])->middleware("Authenticate");





