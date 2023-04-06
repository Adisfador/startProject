<?php

namespace App\Models;

use App\RMVC\Database\Database;


abstract class Model
{

     protected $config;
     protected $TableName;
     protected $queryBuilder;

 
    public function query(){
        return $this->queryBuilder;
    }

    public function __construct()
    {


        $this->config  = require(__DIR__ . '/../../config/DBconfig.php');

        $this->queryBuilder = Database::insatnce();
        $this->queryBuilder->connect($this->config);
        $this->queryBuilder->setTable($this->TableName);
    }
}
