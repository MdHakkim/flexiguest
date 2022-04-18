<?php
namespace App\Validation;


class CommonValidation{
    public $Db;
    public function __construct(){
        $this->Db = \Config\Database::connect();
    }

    public function emailVerification(string $str, string $fields, array $data){
        $email = $data['CUST_EMAIL'];
        $sql="SELECT CUST_EMAIL FROM FLXY_CUSTOMER WHERE CUST_EMAIL='$email'";
        $response = $this->Db->query($sql)->getNumRows();
        return ($response==0 ? true : false);
    }
}