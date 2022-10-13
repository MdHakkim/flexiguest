<?php

namespace App\Libraries;

namespace App\Libraries;

use App\Controllers\NotificationController;
use \Firebase\JWT\JWT;

class Notification
{
  public $Db;

  public function __construct()
  {
    $this->flag = 0;
    $this->Db = \Config\Database::connect();
    $this->NotificationEmail = new NotificationController();
    helper(['common']);
  }

  public function ShowAll($realtime = 0)
  {

    $url  = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $NotificationOutput = '';

    $cond = '';
    $five_minutes_ago = '';
    $UserID = session()->get('USR_ID');
    $datetime = date('Y-m-d H:i:s');

    $cond = "AND NOTIFICATION_DATE_TIME <= '" . $datetime . "'";

    $sql = "SELECT TOP(5) NOTIF_TRAIL_ID, NOTIF_TRAIL_DEPARTMENT, NOTIF_TRAIL_USER, NOTIF_TRAIL_NOTIFICATION_ID, NOTIF_TRAIL_RESERVATION, NOTIF_TRAIL_READ_STATUS, NOTIFICATION_TYPE,NOTIFICATION_TEXT,NOTIFICATION_DATE_TIME, NOTIF_TY_DESC,NOTIF_TY_ICON,NOTIFICATION_TRAIL_SEND FROM FLXY_NOTIFICATION_TRAIL INNER JOIN FLXY_NOTIFICATIONS ON NOTIFICATION_ID = NOTIF_TRAIL_NOTIFICATION_ID INNER JOIN FLXY_NOTIFICATION_TYPE ON NOTIF_TY_ID = NOTIFICATION_TYPE WHERE NOTIF_TRAIL_USER = $UserID $cond ORDER BY NOTIF_TRAIL_ID DESC";
    $response = $this->Db->query($sql)->getResultArray();
    $responseCount = $this->Db->query($sql)->getNumRows();

    if ($responseCount > 0) {

      $NotificationOutput .= <<<EOD
            
                
                <ul class="list-group list-group-flush">
            EOD;
      foreach ($response as $notif) {
        if ($notif['NOTIFICATION_TRAIL_SEND'] != 1)
          $this->NotificationEmail->triggerNotificationEmail($notif['NOTIF_TRAIL_NOTIFICATION_ID']);
        $NOTIFICATION_TEXT = substr(strip_tags($notif['NOTIFICATION_TEXT'], '<p>'), 0, 25) . '...';
        $color =  ($notif['NOTIF_TRAIL_READ_STATUS'] == 0) ? "color:#0d6efd" : "color:#ddd";
        $background_color = ($notif['NOTIF_TRAIL_READ_STATUS'] == 0) ? "#5a8dee" : "#69809a";
        $display = ($notif['NOTIF_TRAIL_READ_STATUS'] == 0) ? "block" : "none";

        $notification_time = $this->getTime($notif['NOTIFICATION_DATE_TIME']);

        $NotificationOutput .= <<<EOD
                
                <li class="list-group-item list-group-item-action dropdown-notifications-item" rel="{$notif['NOTIF_TRAIL_ID']}">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3 notifi-icon-all" style="{$color}" id="notifi-icon-{$notif['NOTIF_TRAIL_ID']}">
                    {$notif['NOTIF_TY_ICON']}
                    </div>
                  <div class="flex-grow-1 notification-text">
                    <h6 class="mb-1">New {$notif['NOTIF_TY_DESC']}</h6>
                    <p class="mb-0 pb-0">{$NOTIFICATION_TEXT}</p>
                    <small class="text-muted">{$notification_time}</small>
                  </div>
                  <div class="dropdown-notifications-actions flex-shrink-0" >
                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                      id="notifications-read-{$notif['NOTIF_TRAIL_ID']}"><span class="badge badge-dot" style="display:{$display}"></span
                    ></a>
                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                      ><span class="bx bx-x"></span
                    ></a>
                  </div>
                </div>
              </li>
              EOD;
      }

      $NotificationOutput .= <<<EOD
            <li class="dropdown-menu-footer border-top">
            <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3" id="ViewAll">
                View all notifications
            </a>
            </li>
            EOD;

      $NotificationOutput .= <<<EOD
            
            </ul>
          
            EOD;
    } else {

      $NotificationOutput .= <<<EOD
                    
              <ul class="list-group list-group-flush">
            EOD;

      $NotificationOutput .= <<<EOD
            
            <li class="dropdown-menu-footer border-top">
            <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3">
                No new Notifications found
            </a>
            </li>
            EOD;

      $NotificationOutput .= <<<EOD
              <li class="dropdown-menu-footer border-top">
              <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3" id="ViewAll">
                  View all notifications
              </a>
              </li>
            EOD;

      $NotificationOutput .= <<<EOD
            
            </ul>
          
            EOD;
    }


    // if($this->flag == 0) 
    // {        
    //   $NotificationOutput.= <<<EOD
    //       <li class="dropdown-menu-footer border-top">
    //       <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3" id="ViewAll">
    //           View all notifications
    //       </a>
    //       </li>
    //       EOD;
    //       $this->flag = 1;
    // }

    return $NotificationOutput;
  }

  public function NotificationCount()
  {
    $UserID = session()->get('USR_ID');
    $datetime = date('Y-m-d H:i:s');
    $sqlStatusCount = "SELECT NOTIF_TRAIL_ID FROM FLXY_NOTIFICATION_TRAIL WHERE NOTIF_TRAIL_USER = $UserID AND NOTIF_TRAIL_READ_STATUS = '0' AND NOTIF_TRAIL_DATETIME <= '$datetime'";
    $responseStatusCount = $this->Db->query($sqlStatusCount)->getNumRows();
    return $responseStatusCount ?? 0;
  }

  public function getTime($startDate)
  {
    $endDate = strtotime(date('Y-m-d H:i:s'));
    $dateDiff = intval(($endDate - strtotime($startDate)) / 60);

    $minutes     = ($dateDiff % 60);
    $minutesText = $minutes . ' minutes ago';
    $hours       = intval($dateDiff / 60);
    $hoursText   = $hours . ' hours ago';
    $days        = intval($dateDiff / 60 / 24);
    $daysText    = $days . ' days ago';

    if ($days > 0)
      $time = $daysText;
    else if ($hours > 0)
      $time = $hoursText;
    else if ($minutes > 0)
      $time = $minutesText;
    else
      $time = "0 minutes ago";

    return $time;
  }
}
