<?php

use Config\Services;

function getMethodName()
{
    /** Get Method Name as Page Title */

    $router = service('router');
    $method = $router->methodName();
    return trim(ucwords(implode(' ', preg_split('/(?=[A-Z])/', $method))));
}

function addActivityLog($activity_group, $activity_type, $element_id, $log_desc)
{
    /** Add Log entry */

    $Db = \Config\Database::connect();
    $session = \Config\Services::session();
    $request = \Config\Services::request();

    $data = [   "USR_ID" => $session->USR_ID,
                "USR_IP_ADDR" => $request->getIPAddress(),
                "LOG_DATETIME" => date("Y-m-d H:i:s"),
                "AC_GP_ID" => $activity_group,
                "AC_TY_ID" => $activity_type,
                "ELEMENT_ID" => $element_id,
                "LOG_ACTION_DESCRIPTION" => $log_desc
            ];
    if(!empty($log_desc))
        $Db->table('FLXY_ACTIVITY_LOG')->insert($data);    
}

function setNullOnEmpty($val)
{
    $val = trim($val);

    if($val != '')
        return $val;
    else
        return NULL;    
}