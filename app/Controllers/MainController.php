<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class MainController extends ResourceController
{
    use ResponseTrait;
    public $Db;
    public function __construct()
    {
        $this->Db = \Config\Database::connect();
      
        //OR
      
        //$this->db = db_connect();
    }
    public function index()
    {
        // print_r("testin")
        return view('welcome_message');
    }

    public function select(){
        $sql="SELECT * FROM TEST_TB";
        $return = $this->Db->query($sql)->getResultArray();
        print_r($return);
        exit;
    }

    public function insert(){
        // echo "Test";exit;
       
        try{
        //     $data = array('name_e'=> 'MOHAMED', 
        //         'email'=>  'hakkim@gmail.com', 
        //         'age'	=>  '35'
        //     );
        // $this->Db->insert('TEST_TB', $data);
        
            $sql="insert into test_tb(id,name_e,email,age) values(4,'MOHAMED','hakkim@gmail.com','32')";
            $return = $this->Db->query($sql);
            
            if ( ! $return)
            {
                throw new Exception();
            }
            dd($return);
            return $this->respond($return);
        }
        catch (Exception $e)
        {
            return $this->respond($e->errors());
        }
    }
}
