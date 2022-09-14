<?php

use App\Models\BrandingLogo;
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

function setZeroOnEmpty($val)
{
    $val = trim($val);

    if(!empty($val))
        return $val;
    else
        return '0.00';    
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

function getValueFromTable($column, $cond, $table)
{
    $Db = \Config\Database::connect();
    $param = ['COLUMN' => $column, 'COND' => $cond, 'TABLENAME' => $table];

    $sql = "SELECT ".$param['COLUMN']."
            FROM ".$param['TABLENAME']."
            WHERE ".$cond."";

    $response = $Db->query($sql,$param)->getRow()->{$param['COLUMN']};
    return $response;    
}


function updateValuesinTable($columns, $conditions, $table)
{
    if($columns == NULL) return NULL;

    $sql = "UPDATE ".$table." SET ";

    $update_cols = '';
    if($columns != NULL)
    {
        foreach($columns as $column => $value){
            $update_cols .= $column." = '".$value."', ";
        }
        $update_cols = rtrim($update_cols, ", ");
    }

    //echo "<pre>"; print_r($columns); echo "</pre>"; echo "<pre>"; print_r($conditions); echo "</pre>"; echo "<pre>"; print_r($table); echo "</pre>"; exit;

    $sql .= $update_cols;

    $update_conds = '';
    if($conditions != NULL)
    {
        foreach($conditions as $column => $value){
            $update_conds .= $column." = '".$value."' AND ";
        }
        $update_conds = rtrim($update_conds, " AND ");
    }
    $sql .= " WHERE ".$update_conds;

    return $sql; 
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

function mergeCustomer($pmCustId, $ogCustId, $mergeFields = [])
{
    $Db = \Config\Database::connect();

    $param = ['pmCustId'=> $pmCustId, 'ogCustId'=> $ogCustId];

    $cust_sql = "SELECT * FROM FLXY_CUSTOMER";
    $pm_cust_sql = $cust_sql ." WHERE CUST_ID=:pmCustId:";
    $og_cust_sql = $cust_sql ." WHERE CUST_ID=:ogCustId:";

    $pm_cust_data = $Db->query($pm_cust_sql,$param)->getRowArray();
    $og_cust_data = $Db->query($og_cust_sql,$param)->getRowArray();

    $up_cust_data = [];
    $ignore_cols = ['CUST_ID','CUST_ACTIVE'];

    if($pm_cust_data != NULL && $og_cust_data != NULL)
    {
        $update_custfields_desc = '';
        foreach($pm_cust_data as $col => $value)
        {
            if(in_array($col, $ignore_cols)) continue;

            if( array_key_exists($col, $og_cust_data) && in_array($col, $mergeFields) && !empty(trim($value)) ){
                $up_cust_data[$col] = $value;
                $update_custfields_desc .= "<b>".str_replace('CUST_', '', $col) . ": </b> '" . $og_cust_data[$col] . "' -> '". $pm_cust_data[$col]."'<br/>";
            }
        }

        /*
        echo "<pre>"; print_r($mergeFields); echo "</pre>"; echo "<pre>";        
        echo "<pre>"; print_r($up_cust_data); echo "</pre>"; echo "<pre>";        
        exit;
        */
        
        //Update Customer Table
        if($up_cust_data != NULL)
            $Db->query(updateValuesinTable($up_cust_data, ['CUST_ID' => $ogCustId], 'FLXY_CUSTOMER'));

        
        $changed_cust_data = $Db->query($og_cust_sql,$param)->getRowArray();

        // Update Reservations Table
        $get_resv_sql = "SELECT * FROM FLXY_RESERVATION WHERE RESV_NAME = ".$pmCustId."";
        $get_resv_response = $Db->query($get_resv_sql)->getResultArray();
        foreach($get_resv_response as $resv_response){
            //$resv_sql = "UPDATE FLXY_RESERVATION SET RESV_NAME = ".$ogCustId." WHERE RESV_NAME = ".$pmCustId." AND RESV_ID = ".$resv_response['RESV_ID']."";
            $Db->query(updateValuesinTable(['RESV_NAME' => $ogCustId], ['RESV_NAME' => $pmCustId, 'RESV_ID' => $resv_response['RESV_ID']], 'FLXY_RESERVATION'));
            addActivityLog(1, 26, $resv_response['RESV_ID'], 
                                  "<b>Changed Guest: </b> From '" . trim($pm_cust_data['CUST_FIRST_NAME']." ".$pm_cust_data['CUST_MIDDLE_NAME']." ".$pm_cust_data['CUST_LAST_NAME']) ."' 
                                                            to '" . trim($og_cust_data['CUST_FIRST_NAME']." ".$og_cust_data['CUST_MIDDLE_NAME']." ".$og_cust_data['CUST_LAST_NAME']) ."'<br/>");
        }

        // Update Accompany Profile Table
        $get_accprof_sql = "SELECT * FROM FLXY_ACCOMPANY_PROFILE WHERE ACCOMP_CUST_ID = ".$pmCustId."";
        $get_accprof_response = $Db->query($get_accprof_sql)->getResultArray();
        if($get_accprof_response != NULL){
            foreach($get_accprof_response as $accprof_response){
                //$accprof_sql = "UPDATE FLXY_ACCOMPANY_PROFILE SET ACCOMP_CUST_ID = ".$ogCustId." WHERE ACCOPM_ID = ".$accprof_response['ACCOPM_ID']."";
                $Db->query(updateValuesinTable(['ACCOMP_CUST_ID' => $ogCustId], ['ACCOPM_ID' => $accprof_response['ACCOPM_ID']], 'FLXY_ACCOMPANY_PROFILE'));
                addActivityLog(1, 26, $accprof_response['ACCOMP_REF_RESV_ID'], 
                                    "<b>Changed Accompanied Guest: </b> From '" . trim($pm_cust_data['CUST_FIRST_NAME']." ".$pm_cust_data['CUST_MIDDLE_NAME']." ".$pm_cust_data['CUST_LAST_NAME']) ."' 
                                                                          to '" . trim($og_cust_data['CUST_FIRST_NAME']." ".$og_cust_data['CUST_MIDDLE_NAME']." ".$og_cust_data['CUST_LAST_NAME']) ."'<br/>");
            }
        }

        // Update Concierge Requests Table
        /*$cncrg_sql = "UPDATE FLXY_CONCIERGE_REQUESTS SET CR_CUSTOMER_ID = ".$ogCustId.", CR_GUEST_NAME = '".trim($changed_cust_data['CUST_FIRST_NAME']." ".$changed_cust_data['CUST_MIDDLE_NAME']." ".$changed_cust_data['CUST_LAST_NAME'])."', 
                                                         CR_GUEST_EMAIL = '".$changed_cust_data['CUST_EMAIL']."', CR_GUEST_PHONE = '".(!empty($changed_cust_data['CUST_MOBILE']) ? $changed_cust_data['CUST_MOBILE'] : $changed_cust_data['CUST_PHONE'])."' 
                      WHERE CR_CUSTOMER_ID = ".$pmCustId."";*/

        $Db->query(updateValuesinTable(['CR_CUSTOMER_ID' => $ogCustId, 
                                        'CR_GUEST_NAME'  => trim($changed_cust_data['CUST_FIRST_NAME']." ".$changed_cust_data['CUST_MIDDLE_NAME']." ".$changed_cust_data['CUST_LAST_NAME']),
                                        'CR_GUEST_PHONE' => (!empty($changed_cust_data['CUST_MOBILE']) ? $changed_cust_data['CUST_MOBILE'] : $changed_cust_data['CUST_PHONE'])], 
                                       ['CR_CUSTOMER_ID' => $pmCustId], 'FLXY_CONCIERGE_REQUESTS'));        
        
        
        //Update Customer Memberships
        $del_cm = "DELETE FROM FLXY_CUSTOMER_MEMBERSHIP 
                   WHERE CUST_ID = ".$pmCustId." 
                   AND MEM_ID IN (SELECT MEM_ID FROM FLXY_CUSTOMER_MEMBERSHIP WHERE CUST_ID = ".$ogCustId.")";
        $Db->query($del_cm);

        $get_cusmem_sql = "SELECT *, (SELECT MEM_DESC FROM FLXY_MEMBERSHIP WHERE MEM_ID = CM.MEM_ID) AS MEM_DESC
                           FROM FLXY_CUSTOMER_MEMBERSHIP CM
                           WHERE CM.CUST_ID = ".$pmCustId." 
                           AND CM.MEM_ID NOT IN (SELECT MEM_ID FROM FLXY_CUSTOMER_MEMBERSHIP WHERE CUST_ID = ".$ogCustId.")";

        $get_cusmem_response = $Db->query($get_cusmem_sql)->getResultArray();
        if($get_cusmem_response != NULL && in_array('CUST_MEMBERSHIPS', $mergeFields)){ // If Memberships selected to copy over
            foreach($get_cusmem_response as $cusmem_response){
                /*$cusmem_sql = "UPDATE FLXY_CUSTOMER_MEMBERSHIP SET CUST_ID = ".$ogCustId." 
                               WHERE CM_ID = ".$cusmem_response['CM_ID']."";*/
                $Db->query(updateValuesinTable(['CUST_ID' => $ogCustId], ['CM_ID' => $cusmem_response['CM_ID']], 'FLXY_CUSTOMER_MEMBERSHIP'));
                $update_custfields_desc .= "<b>Added Membership: </b> '" . $cusmem_response['MEM_DESC'] . "'<br/>";
                //$Db->query($cusmem_sql);
            }
        }

        //Update Customer Preferences
        $del_cm = "DELETE FROM FLXY_CUSTOMER_PREFERENCE 
                   WHERE CUST_ID = ".$pmCustId." 
                   AND PF_CD_ID IN (SELECT PF_CD_ID FROM FLXY_CUSTOMER_PREFERENCE WHERE CUST_ID = ".$ogCustId.")";
        $Db->query($del_cm);

        $get_cuspref_sql = "SELECT *, (SELECT PF_CD_DESC FROM FLXY_PREFERENCE_CODE WHERE PF_CD_ID = CP.PF_CD_ID) AS PF_CD_DESC
                           FROM FLXY_CUSTOMER_PREFERENCE CP
                           WHERE CP.CUST_ID = ".$pmCustId." 
                           AND CP.PF_CD_ID NOT IN (SELECT PF_CD_ID FROM FLXY_CUSTOMER_PREFERENCE WHERE CUST_ID = ".$ogCustId.")";

        $get_cuspref_response = $Db->query($get_cuspref_sql)->getResultArray();
        if($get_cuspref_response != NULL && in_array('CUST_PREFERENCES', $mergeFields)){ // If Preferencess selected to copy over
            foreach($get_cuspref_response as $cuspref_response){
                $Db->query(updateValuesinTable(['CUST_ID' => $ogCustId], ['CPF_ID' => $cuspref_response['CPF_ID']], 'FLXY_CUSTOMER_PREFERENCE'));
                $update_custfields_desc .= "<b>Added Preference: </b> '" . $cuspref_response['PF_CD_DESC'] . "'<br/>";
                //$Db->query($cuspref_sql);
            }
        }


        // Update Documents Table
        //$doc_sql = "UPDATE FLXY_DOCUMENTS SET DOC_CUST_ID = ".$ogCustId." WHERE DOC_CUST_ID = ".$pmCustId."";
        $Db->query(updateValuesinTable(['DOC_CUST_ID' => $ogCustId], ['DOC_CUST_ID' => $pmCustId], 'FLXY_DOCUMENTS'));


        // Update Feedback Table
        //$fb_sql = "UPDATE FLXY_FEEDBACK SET FB_CUST_ID = ".$ogCustId." WHERE FB_CUST_ID = ".$pmCustId."";
        $Db->query(updateValuesinTable(['FB_CUST_ID' => $ogCustId], ['FB_CUST_ID' => $pmCustId], 'FLXY_FEEDBACK'));


        // Update Laundry Amenities Orders Table
        //$la_sql = "UPDATE FLXY_LAUNDRY_AMENITIES_ORDERS SET LAO_CUSTOMER_ID = ".$ogCustId." WHERE LAO_CUSTOMER_ID = ".$pmCustId."";
        $Db->query(updateValuesinTable(['LAO_CUSTOMER_ID' => $ogCustId], ['LAO_CUSTOMER_ID' => $pmCustId], 'FLXY_LAUNDRY_AMENITIES_ORDERS'));


        // Update Maintenance Table
        //$mtnc_sql = "UPDATE FLXY_MAINTENANCE SET CUST_NAME = ".$ogCustId." WHERE CUST_NAME = ".$pmCustId."";
        $Db->query(updateValuesinTable(['CUST_NAME' => $ogCustId], ['CUST_NAME' => $pmCustId], 'FLXY_MAINTENANCE'));


        //Update Rate Code Negotiated Rate Table
        $del_rcneg = "  DELETE FROM FLXY_RATE_CODE_NEGOTIATED_RATE 
                        WHERE PROFILE_ID = ".$pmCustId." AND PROFILE_TYPE = 1 
                        AND RT_CD_ID IN (SELECT RT_CD_ID FROM FLXY_RATE_CODE_NEGOTIATED_RATE 
                                         WHERE PROFILE_ID = ".$ogCustId." AND PROFILE_TYPE = 1)";
        $Db->query($del_rcneg);

        $get_rcneg_sql = "SELECT * FROM FLXY_RATE_CODE_NEGOTIATED_RATE 
                          WHERE PROFILE_ID = ".$pmCustId." AND PROFILE_TYPE = 1  
                          AND RT_CD_ID NOT IN (SELECT RT_CD_ID FROM FLXY_RATE_CODE_NEGOTIATED_RATE 
                                               WHERE PROFILE_ID = ".$ogCustId." AND PROFILE_TYPE = 1)";

        $get_rcneg_response = $Db->query($get_rcneg_sql)->getResultArray();
        if($get_rcneg_response != NULL && in_array('RATE_CODES', $mergeFields)){ // If Rate Codes selected to copy over
            foreach($get_rcneg_response as $rcneg_response){
                //$rcneg_sql = "UPDATE FLXY_RATE_CODE_NEGOTIATED_RATE SET PROFILE_ID = ".$ogCustId." WHERE NG_RT_ID = ".$rcneg_response['NG_RT_ID']."";
                $Db->query(updateValuesinTable(['PROFILE_ID' => $ogCustId], ['NG_RT_ID' => $rcneg_response['NG_RT_ID']], 'FLXY_RATE_CODE_NEGOTIATED_RATE'));
            }
        }        

        // Update Vaccine Details Table
        //$vacc_sql = "UPDATE FLXY_VACCINE_DETAILS SET VACC_CUST_ID = ".$ogCustId." WHERE VACC_CUST_ID = ".$pmCustId."";
        $Db->query(updateValuesinTable(['VACC_CUST_ID' => $ogCustId], ['VACC_CUST_ID' => $pmCustId], 'FLXY_VACCINE_DETAILS'));

        //Delete profile to merge
        $Db->table('FLXY_CUSTOMER')->delete(['CUST_ID' => $pmCustId]);

        //Add Customer Log
        addActivityLog(3, 36, $ogCustId, "<b>Merged Profile with: </b> '" . trim($pm_cust_data['CUST_FIRST_NAME']." ".$pm_cust_data['CUST_MIDDLE_NAME']." ".$pm_cust_data['CUST_LAST_NAME']) ."'<br/>" . $update_custfields_desc); 
        
        return true;
    }
}

function getPreferenceGroupList()
{
    $Db = \Config\Database::connect();

    $sql = "SELECT  PF_GR_ID, RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(PF_GR_CODE, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS PF_GR_CODE,
                    PF_GR_DESC

            FROM FLXY_PREFERENCE_GROUP
            WHERE PF_GR_STATUS = 1";
            
    $response = $Db->query($sql)->getResultArray();

    $options = array();

    foreach ($response as $row) {
        $options[] = array( "id" => $row['PF_GR_ID'], "value" => $row['PF_GR_CODE']. ' | ' .$row['PF_GR_DESC']);
    }

    return $options;
}

function getPreferenceCodeList($custId = 0, $pfGrp = 0)
{
    $Db = \Config\Database::connect();
    $request = \Config\Services::request();

    
    $search = $request->getPost('search');

        $sql = "SELECT  PF_CD_ID, RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(PF_CD_CODE, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS PF_CD_CODE,
                        PF_CD_DESC

                FROM FLXY_PREFERENCE_CODE
                WHERE PF_CD_STATUS = 1";

        if (!empty($pfGrp)) {
            $sql .= " AND PF_GR_ID = '$pfGrp'";
        }
        if (trim($search) != '') {
            $sql .= " AND (PF_CD_CODE LIKE '%$search%' OR PF_CD_DESC LIKE '%$search%')";
        }
        if (!empty($custId)) {
            $sql .= " AND PF_CD_ID IN (SELECT PF_CD_ID FROM FLXY_CUSTOMER_PREFERENCE WHERE CUST_ID = $custId)";
        }

        $sql .= " ORDER BY PF_CD_DIS_SEQ ASC";

        $response = $Db->query($sql)->getResultArray();

        $options = array();

        foreach ($response as $row) {
            $options[] = array("id" => $row['PF_CD_ID'], "code" => $row['PF_CD_CODE'], "text" => $row['PF_CD_DESC']);
        }

        return !empty($pfGrp) ? $options : [];
}

    
function geUsersList()
{
        $Db = \Config\Database::connect();

        $sql = "SELECT USR_ID, USR_NAME, USR_EMAIL, USR_ROLE_ID
                FROM FlXY_USERS WHERE USR_CUST_ID IS NULL AND USR_STATUS = 1";

        $response = $Db->query($sql)->getResultArray();

        return $response;
}

function brandingLogo()
{
    $BrandingLogo = new BrandingLogo();
    $logo = $BrandingLogo->first();

    if(!empty($logo))
        return base_url($logo['BL_URL']);

    return "";
}

function isWeb()
{
    return !str_contains(current_url(), "/api/");
}