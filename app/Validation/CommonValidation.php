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

    public function strongPassword(string $str, string $field, array $data)
    {
        $fields = explode(',', $field);
        if (preg_match('/\d/', $data[$fields[0]]) && preg_match('/[a-zA-Z]/', $data[$fields[0]]) && preg_match('/\W/', $data[$fields[0]]) && !preg_match("/\s/", $data[$fields[0]])) {
            return true;
        }

        return false;
    }

    public function todayOrAfter(string $str, string $field, array $data)
    {
        $fields = explode(',', $field);
        $date = date("Y-m-d", strtotime($data[$fields[0]]));
        
        $date_now = date("Y-m-d"); // this format is string comparable
        if($date < $date_now)
            return false;
    }

    public function afterNow(string $str, string $field, array $data)
    {
        $fields = explode(',', $field);
        $datetime = date("Y-m-d H:i:s", strtotime($data[$fields[0]] . " " . $data[$fields[1]]));

        $date_now = date("Y-m-d H:i:s"); // this format is string comparable
        if($date_now >= $datetime)
            return false;
    }

    public function afterDateTime(string $str, string $field, array $data)
    {
        $fields = explode(',', $field);
        
        $datetime = date("Y-m-d H:i:s", strtotime($data[$fields[0]] . " " . $data[$fields[1]]));
        $datetime2 = date("Y-m-d H:i:s", strtotime($data[$fields[2]] . " " . $data[$fields[3]]));

        if($datetime >= $datetime2)
            return false;
    }

    public function customInArray(string $str)
    {
        $possible_values = ['Processing', 'Delivered', 'Rejected'];
        if(in_array($str, $possible_values))
            return true;

        return false;
    }
}