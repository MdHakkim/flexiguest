<?php
namespace App\Validation;

class CustomRules{

  public $Db;
 
  public function __construct(){
    $this->Db = \Config\Database::connect();
  }

  // Rule is to validate mobile number digits
  public function compareDate(){
    
    $date_fields = array( array('start' => 'RT_CL_BEGIN_DT', 'end' => 'RT_CL_END_DT'), 
                          array('start' => 'RT_CT_BEGIN_DT', 'end' => 'RT_CT_END_DT'), 
                          array('start' => 'RT_CD_BEGIN_SELL_DT', 'end' => 'RT_CT_END_DT'), 
                          array('start' => 'RT_CD_START_DT', 'end' => 'RT_CD_END_DT'), 
                          array('start' => 'rep_RT_CD_START_DT', 'end' => 'rep_RT_CD_END_DT'), 
                          array('start' => 'NG_RT_START_DT', 'end' => 'NG_RT_END_DT'), 
                          array('start' => 'PKG_CD_START_DT', 'end' => 'PKG_CD_END_DT'),
                          array('start' => 'RSV_ITM_BEGIN_DATE', 'end' => 'RSV_ITM_END_DATE'),
                          array('start' => 'CM_TODAY', 'end' => 'CM_EXPIRY_DATE'));

                       
    foreach($date_fields as $date_field)
    {
        if(isset($_POST[$date_field['start']]) && isset($_POST[$date_field['end']]))
        {
          $startDate = strtotime($_POST[$date_field['start']]);
          $endDate   = strtotime($_POST[$date_field['end']]);

          if ($endDate >= $startDate || $endDate == '')
              return true;
          else {
              return false;
          }  
        }
    }
    
    return false;
  }

  public function dateOverlapCheck(string $str, string $fields, array $data)
  {
    /**
     *  Check Date Overlap for Rate Code Detail 
     */

    $rate_code_id = isset($data['RT_CD_ID']) ? $data['RT_CD_ID'] : (isset($data['rep_RT_CD_ID']) ? $data['rep_RT_CD_ID'] : '');
    $rate_code_detail_id = isset($data['RT_CD_DT_ID']) ? $data['RT_CD_DT_ID'] : '';

    $comma_values = explode(',', $fields);    
    $date_field = array_shift($comma_values); // get first array item

    $check_date   = $data[$date_field];
    $room_types   = $comma_values;
    
    $sql = "SELECT RT_CD_DT_ID FROM FLXY_RATE_CODE_DETAIL 
            WHERE RT_CD_ID = '".$rate_code_id."'";
    
    if(!empty($rate_code_detail_id))
      $sql .= " AND RT_CD_DT_ID != '".$rate_code_detail_id."'";
            
    $sql .= " AND ('".$check_date."' BETWEEN RT_CD_START_DT AND RT_CD_END_DT)";

    if($room_types != NULL)
    {
      $sql .= " AND (";
      foreach($room_types as $room_type)
      {
          $sql .= " CONCAT(',', RT_CD_DT_ROOM_TYPES, ',') LIKE '%,".$room_type.",%' OR";
      }

      $sql = rtrim($sql, ' OR');
      $sql .= ")";
    }
    else 
      return true;        

    $response = $this->Db->query($sql)->getNumRows();
    return ($response==0 ? true : false);
  }

  public function dateOverlapCheckNR(string $str, string $fields, array $data)
  {
    /**
     *  Check Date Overlap for Negotiated Rate 
     */
    
    $rate_code_id = isset($data['neg_RT_CD_ID']) ? $data['neg_RT_CD_ID'] : '';
    $negotiated_rate_id = isset($data['NG_RT_ID']) ? $data['NG_RT_ID'] : '';

    $comma_values = explode(',', $fields);    
    $date_field = array_shift($comma_values); // get first array item

    $check_date = $data[$date_field];
    $profiles   = $comma_values;
    
    $sql = "SELECT NG_RT_ID FROM FLXY_RATE_CODE_NEGOTIATED_RATE 
            WHERE RT_CD_ID = '".$rate_code_id."'";
    
    if(!empty($negotiated_rate_id))
      $sql .= " AND NG_RT_ID != '".$negotiated_rate_id."'";
            
    $sql .= " AND ('".date('Y-m-d', strtotime($check_date))."' BETWEEN NG_RT_START_DT AND NG_RT_END_DT)";

    if($profiles != NULL)
    {
      $sql .= " AND (";
      foreach($profiles as $profile_string)
      {
          $profile_data = explode('_', $profile_string);
          $sql .= " CONCAT_WS('_',PROFILE_ID,PROFILE_TYPE) LIKE '%".$profile_data[3]."_".$profile_data[2]."%' OR";
      }

      $sql = rtrim($sql, ' OR');
      $sql .= ")";
    }
    else 
      return true;        

    $response = $this->Db->query($sql)->getNumRows();
    return ($response==0 ? true : false);
  }

  public function checkAvailableItemQty(string $str, string $fields, array $data)
  {
    /**
     *  Check Item QTY is less than the available QTY 
     */

     if($data['RSV_ITM_QTY'] < $fields )
     {
      return true;
     }
      else {
          return false;
      }  

  }

  public function itemAvailableCheck(string $str, string $fields, array $data)
  {
    /**
     *  Check Item QTY is less than the available QTY 
     */

    $RSV_ITM_BEGIN_DATE = date('Y-m-d',(strtotime($data['RSV_ITM_BEGIN_DATE'])));
    $RSV_ITM_END_DATE   =  date('Y-m-d',(strtotime($data['RSV_ITM_END_DATE'])));

    $sql = "SELECT FLXY_DAILY_INVENTORY.ITM_ID FROM FLXY_DAILY_INVENTORY INNER JOIN FLXY_ITEM ON FLXY_ITEM.ITM_ID = FLXY_DAILY_INVENTORY.ITM_ID WHERE ('".$RSV_ITM_BEGIN_DATE."' BETWEEN ITM_DLY_BEGIN_DATE AND ITM_DLY_END_DATE) AND ('".$RSV_ITM_END_DATE."' BETWEEN ITM_DLY_BEGIN_DATE AND ITM_DLY_END_DATE) AND FLXY_DAILY_INVENTORY.ITM_ID = ".$data['RSV_ITM_ID'];                 

    $response = $this->Db->query($sql)->getNumRows();

     if($response == 0)
     {
      return false;
     }
      else {
          return true;
      }  

  }


  public function itemDateOverlapCheck(string $str, string $fields, array $data)
  {
    /**
     *  Check Item dates are overlapped or not */

    $RSV_ITM_BEGIN_DATE = date('Y-m-d',(strtotime($data['RSV_ITM_BEGIN_DATE'])));
    $RSV_ITM_END_DATE   =  date('Y-m-d',(strtotime($data['RSV_ITM_END_DATE'])));


    if($data['RSV_ID'] == '')
    $sql = "SELECT * FROM FLXY_RESERVATION_ITEM WHERE RSV_ITM_ID = '".$data['RSV_ITM_ID']."' AND ('".$RSV_ITM_BEGIN_DATE."' BETWEEN RSV_ITM_BEGIN_DATE AND RSV_ITM_END_DATE) AND ('".$RSV_ITM_END_DATE."' BETWEEN RSV_ITM_BEGIN_DATE AND RSV_ITM_END_DATE)"; 
    else
    $sql = "SELECT * FROM FLXY_RESERVATION_ITEM WHERE RSV_ID = '".$data['RSV_ID']."' AND RSV_ITM_ID = '".$data['RSV_ITM_ID']."' AND ('".$RSV_ITM_BEGIN_DATE."' BETWEEN RSV_ITM_BEGIN_DATE AND RSV_ITM_END_DATE) AND ('".$RSV_ITM_END_DATE."' BETWEEN RSV_ITM_BEGIN_DATE AND RSV_ITM_END_DATE)";


   
    $response = $this->Db->query($sql)->getNumRows();
    return ($response==0 ? true : false);
    


  }

  	//Create strong password 
	public function is_password_strong($password = '')
	{
   
		if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password)) {
      return TRUE;
    }
    else
		return FALSE;
	}
	//strong password end

  

}