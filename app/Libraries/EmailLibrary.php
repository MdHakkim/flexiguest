<?php
namespace App\Libraries;
use \Firebase\JWT\JWT;

class EmailLibrary{
    protected $email;
    public function __construct(){
        $this->email = \Config\Services::email();
    }
    public function preCheckInEmail($rawparam){
        $toEmail = $rawparam[0]['CUST_EMAIL'];
        // $dataFormat = $rawparam[0]['CUST_EMAIL'].','.$rawparam[0]['RESV_ID'];
        // $key = getenv('JWT_KEY_WEBLINK');
        // $iat = time(); // current timestamp value
        // $nbf = $iat;
        // $exp = $iat + 2592000;
        // $payload = array(
        //     "iss" => "Issue by farnek",
        //     "aud" => "Audience that the farnek",
        //     "iat" => $iat, // issued at
        //     "nbf" => $nbf, //not before in seconds
        //     "exp" => $exp, // expire time in seconds
        //     "data" => $dataFormat,
        // );
        // $paramraw['token'] = JWT::encode($payload, $key,'HS256');
        $paramraw['data'] = $rawparam[0];
        $html = view('EmailTemplates/ReservationTemplate',$paramraw);
        $this->email->setFrom('notifications@farnek.com', 'FlexiGuest | Hitek');
        $this->email->setTo($toEmail);
        $this->email->setSubject('Pre Check-in Link');
        $this->email->setMessage($html);//your message here
        if ($this->email->send()) {
            return 'Email successfully sent, please check';
        } else {
            $data = $this->email->printDebugger(['headers']);
            return $data;
        }
    }

    public function requestDocUploadEmail($rawparam , $accompanyemail, $name){
        $paramraw['data'] = $rawparam[0];
        $paramraw['name'] = $name;
        $toEmail = $accompanyemail;
        $html = view('EmailTemplates/RequestSelfDocUpload',$paramraw);
        $this->email->setFrom('notifications@farnek.com', 'FlexiGuest | Hitek');
        $this->email->setTo($toEmail);
        $this->email->setSubject('Request to upload the pre-checkin documents');
        $this->email->setMessage($html);//your message here
        if ($this->email->send()) {
            return true;
        } else {
            $data = $this->email->printDebugger(['headers']);
            return $data;
        }
    }
}
