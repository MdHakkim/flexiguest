<?php
namespace App\Libraries;
use \Firebase\JWT\JWT;

class EmailLibrary{
    protected $email;
    public function __construct(){
        $this->email = \Config\Services::email();
    }
    public function preCheckInEmail($rawparam,$param){
        $toEmail = $rawparam[0]['CUST_EMAIL'];
        $paramraw['data'] = $rawparam[0];
        $paramraw['mode'] = $param;
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
