<?php
namespace App\Validation;


class CommonValidation{
    public $Db;
    public function __construct(){
        $this->Db = \Config\Database::connect();
    }

    public function emailVerification(string $str, string $fields, array $data){
        $email = $data['CUST_EMAIL'];
        $cust_id = !empty($data['CUST_ID']) ? $data['CUST_ID'] : 0;

        $sql="SELECT CUST_EMAIL FROM FLXY_CUSTOMER WHERE CUST_EMAIL='$email' and CUST_ID != $cust_id";
        $response = $this->Db->query($sql)->getNumRows();
        return ($response==0 ? true : false);
    }
}