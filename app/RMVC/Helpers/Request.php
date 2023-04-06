<?php

namespace App\RMVC\Helpers;

class Request {
    public $get=[];
    public $post=[];
    public $request=[];
    public $cooki=[];
    public $files=[];
    public $server=[];

    public function __construct()
    {
        $this->get=$_GET;
        $this->post=$_POST;
        $this->request=$_REQUEST;
        $this->cooki=$_COOKIE;
        $this->files=$_FILES;
        $this->server=$_SERVER;
    }
}