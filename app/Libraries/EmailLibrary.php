<?php
namespace App\Libraries;

class EmailLibrary{
    protected $email;
    public function __construct(){
        $this->email = \Config\Services::email();
    }
    public function preCheckInEmail($rawparam){
        $paramraw['data'] = $rawparam[0];
        $toEmail = $rawparam[0]['CUST_EMAIL'];
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
}
