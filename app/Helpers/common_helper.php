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

function checkValueinTable($column, $value, $table)
{
    $Db = \Config\Database::connect();
    $param = ['COLUMN' => $column, 'VALUE' => $value, 'TABLENAME' => $table];

    $sql = "SELECT ".$param['COLUMN']."
            FROM ".$param['TABLENAME']."
            WHERE ".$param['COLUMN']." = :VALUE:";

    $response = $Db->query($sql,$param)->getNumRows();
    return $response;    
}

function profileTypeList()
{
    $Db = \Config\Database::connect();

    $sql = "SELECT PR_TY_ID, PR_TY_DESC
            FROM FLXY_PROFILE_TYPE
            WHERE 1=1";

    $response = $Db->query($sql)->getResultArray();

    $options = array();

    foreach ($response as $row) {
        $options[] = array("value" => $row['PR_TY_ID'], "desc" => $row['PR_TY_DESC']);
    }

    return $options;
}

function getMembershipTypeList($custId, $mode)
    {
        $Db = \Config\Database::connect();

        $sql = "SELECT MEM_ID, MEM_CODE, MEM_DESC, MEM_EXP_DATE_REQ
                FROM FLXY_MEMBERSHIP WHERE MEM_STATUS = 1";

        // Remove options from dropdown that have already been added by 
        if (!empty($custId) && $mode == 'add') {
            $sql .= " AND MEM_ID NOT IN ( SELECT MEM_ID FROM FLXY_CUSTOMER_MEMBERSHIP 
                                            WHERE CUST_ID = '".$custId."' AND CM_STATUS = 1 )";
        }

        $response = $Db->query($sql)->getResultArray();

        $membershipTypes = array();
        if($response != NULL)
        {
            foreach ($response as $row) {
                $membershipTypes[] = array( "id" => $row['MEM_ID'], "code" => $row["MEM_CODE"], 
                                            "text" => $row["MEM_DESC"], "exp_date_req" => $row["MEM_EXP_DATE_REQ"] == '1' ? '1' : '0');
            }
        }

        return $membershipTypes;
    }