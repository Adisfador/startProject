<?php

namespace App\RMVC\Database;

use PDO;
use Exception;
use App\RMVC\Helpers\SinglTon;




class Database
{

    use QueryBuilder;
    use SinglTon;
    
    private $dbh,$DBConfig= [], $tables = [];
    private static $instanse = NULL;

   
     function connect(array $DBConfig)
    {
        $this->DBConfig=$DBConfig;
        $this->dbh = new PDO(
            "mysql:host=" . $this->DBConfig["host"] . ";dbname=" . $this->DBConfig["name"] . ";charset=" . $this->DBConfig["charset"],
            $this->DBConfig["user"],
            $this->DBConfig["pass"],
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]

        );
    }


    public function delete(int $id)
    {
        
        $this->dbh->query($this->_delete($id));
    }

    public function create($data = [])
    {
        
        return $this->dbh->query($this->_create($data ));
       
    }

    public function update($data = [], $id = [])
    {
        
        return $this->dbh->query($this->_update($data, $id ));
    }

    public function select()
    {
        return $this->dbh->query($this->_select($this->_t,$this->where,$this->join,$this->order,"*",$this->groupBy,$this->having,$this->limit))->fetchAll();

    }

}

