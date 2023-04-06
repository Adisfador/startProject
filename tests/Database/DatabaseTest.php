<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends  TestCase{
    private $Database;
    use  App\RMVC\Database\QueryBuilder;
    protected function setUp() :void{
        // $this->Database= new Database;
        // $this->Database->_create(["kol"=>1,"kol2"=>"test"]);
    }

    protected function tearDown() :void{
        
    }

    public function testCreate(){
        $this->assertEquals("INSERT INTO `` ( `first_name` , `last_name` , `phon` ) VALUES ('first', 'last', '4qf231')",$this->_create(["first_name" => "first", "last_name" => "last","phon" => "4qf231"]));
    }

    public function testUpdate(){
        $this->assertEquals("UPDATE `` SET `first_name` = 'new' ,`phon` = '123'  WHERE `id` = '6'",$this->_update(["first_name" => "new", "phon" => "123"],["id",6]));
    }

}