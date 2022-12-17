<?php
namespace App\Libraries;
use \Firebase\JWT\JWT;

class EmailLibrary{
    protected $email;
    public function __construct(){
        $this->email = \Config\Services::email();
        helper(['form', 'common', 'custom']);
    }
    public function preCheckInEmail($rawparam,$param,$status){
        $toEmail = $rawparam[0]['CUST_EMAIL'];
        $paramraw['data'] = $rawparam[0];
        $paramraw['mode'] = $param;
        $paramraw['status'] = $status;
        
        if($status == 1)
        $subject = 'Check-in Confirmation';
        else if($status == 0)
        $subject = ( $paramraw['mode'] == "QR" ) ? 'Pre Check-in Confirmation' : 'Pre Check-in Link';
        $html = view('EmailTemplates/ReservationTemplate',$paramraw);
        $this->email->setFrom('notifications@farnek.com', 'FLEXIGUEST | HITEK');
        $this->email->setTo($toEmail);
        
        $this->email->setSubject($subject);
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

    public function commonEmail($data)
    {
        $this->email->setFrom($data['from_email'], $data['from_name']);
        $this->email->setTo($data['to_email']);
        $this->email->setSubject($data['subject']);
        $this->email->setMessage($data['html']);

        if ($this->email->send()) {
            return true;
        } else {
            $data = $this->email->printDebugger(['headers']);
            return $data;
        }
    }


    public function notificationEmail($details, $basicInfo){
        $toEmail               = $details['USR_EMAIL'];
        $paramraw['FULL_NAME'] = $details['FULL_NAME'];
        $paramraw['NOTIFICATION_TYPE']      = $basicInfo['NOTIFICATION_TYPE'];
        $paramraw['NOTIFICATION_TEXT']      = $basicInfo['NOTIFICATION_TEXT'];
        $paramraw['NOTIFICATION_URL']       = $basicInfo['NOTIFICATION_URL'];
        $paramraw['NOTIFICATION_TYPE_ID']   = $basicInfo['NOTIFICATION_TYPE_ID'];
        $paramraw['HEADING']                = $basicInfo['HEADING'];
        $paramraw['RESERVATION']            = !empty($basicInfo['RESERVATION'])?$basicInfo['RESERVATION']:'';

        $html = view('EmailTemplates/NotificationEmail',$paramraw);
        $this->email->setFrom('notifications@farnek.com', 'FLEXIGUEST | HITEK');
        $this->email->setTo('subina.kk@farnek.com');
        $this->email->setCC('aamir.sohail@farnek.com');
        $this->email->setSubject($paramraw['NOTIFICATION_TYPE']);
        $this->email->setMessage($html);//your message here
        if ($this->email->send()) {
            return true;
        } else {
            $data = $this->email->printDebugger(['headers']);
            return false;
        }
    }
}
