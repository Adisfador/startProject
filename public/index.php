<?php

use App\RMVC\App;

require_once("../vendor/autoload.php");
require_once("../routes/web.php");

session_start();

App::run();