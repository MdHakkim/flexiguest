<?php
namespace App\Validation;

class CustomRules{

  public $Db;
 
  public function __construct(){
    $this->Db = \Config\Database::connect();
    $this->session = \Config\Services::session();
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
                          array('start' => 'CM_TODAY', 'end' => 'CM_EXPIRY_DATE'),
                          array('start' => 'FIXD_CHRG_BEGIN_DATE', 'end' => 'FIXD_DEPARTURE_UP'),
                          array('start' => 'RSV_PCKG_BEGIN_DATE', 'end' => 'RSV_PCKG_END_DATE'),
                          array('start' => 'BLK_START_DT', 'end' => 'BLK_END_DT'));

                       
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

    if(empty(trim($fields))) return false;

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

  public function checkMaxItems(string $str, string $fields, array $data)
  {    
    return count($_POST['pref_PREFS']) > $fields ? false : true;
  }  

  public function checkAvailableItemQty(string $str, string $fields, array $data)
  {
    /**
     *  Check Item QTY is less than the available QTY 
     */
//ECHO $data['RSV_ITM_QTY']. ''.  $fields;

    $ITM_REMAINING_QTY = $fields;

    $RSV_ITM_BEGIN_DATE  = date('Y-m-d',(strtotime($data['RSV_ITM_BEGIN_DATE'])));
    $RSV_ITM_END_DATE    = date('Y-m-d',(strtotime($data['RSV_ITM_END_DATE'])));
    $RSV_ITM_QTY         = $data['RSV_ITM_QTY'];
    $RSV_ITM_ID         = $data['RSV_ITM_ID'];
    $RSV_PRI_ID         = $data['RSV_PRI_ID'];


    $ITM_RESERVED_QTY = 0;

    $ITM_QTY_IN_STOCK = $this->Db->table('FLXY_ITEM')->select('ITM_QTY_IN_STOCK')->where('ITM_ID', $RSV_ITM_ID)->get()->getRowArray();
    $ITM_QTY_IN_STOCK = (int)$ITM_QTY_IN_STOCK['ITM_QTY_IN_STOCK']; 


    $sql = "SELECT RSV_ITM_QTY FROM FLXY_RESERVATION_ITEM WHERE (('".$RSV_ITM_BEGIN_DATE."' BETWEEN RSV_ITM_BEGIN_DATE AND RSV_ITM_END_DATE) OR ('".$RSV_ITM_END_DATE."' BETWEEN RSV_ITM_BEGIN_DATE AND RSV_ITM_END_DATE)) AND RSV_PRI_ID != '$RSV_PRI_ID'"; 
    $response = $this->Db->query($sql)->getNumRows();
    
        if($response > 0){
          $responseArray = $this->Db->query($sql)->getResultArray();
          $ITM_RESERVED_QTY = $responseArray[0]['RSV_ITM_QTY'];
        }

        (INT)$ITM_REMAINING_QTY = (INT)$ITM_QTY_IN_STOCK - (INT)$ITM_RESERVED_QTY;

     if($RSV_ITM_QTY <= $ITM_REMAINING_QTY )
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

    $RSV_ITM_BEGIN_DATE  = date('Y-m-d',(strtotime($data['RSV_ITM_BEGIN_DATE'])));
    $RSV_ITM_END_DATE    = date('Y-m-d',(strtotime($data['RSV_ITM_END_DATE'])));
    $RSV_ITM_QTY         = $data['RSV_ITM_QTY'];


     $sql = "SELECT ITM_ID, ITM_DLY_QTY, ITM_AVAIL_QTY FROM FLXY_DAILY_INVENTORY WHERE ('".$RSV_ITM_BEGIN_DATE."' BETWEEN ITM_DLY_BEGIN_DATE AND ITM_DLY_END_DATE) AND ('".$RSV_ITM_END_DATE."' BETWEEN ITM_DLY_BEGIN_DATE AND ITM_DLY_END_DATE) AND FLXY_DAILY_INVENTORY.ITM_ID = ".$data['RSV_ITM_ID']; 
    $response = $this->Db->query($sql)->getNumRows();

    if($response > 0){
      $responseArray = $this->Db->query($sql)->getResultArray();
      //$ITM_AVAIL_QTY = $responseArray[0]['ITM_AVAIL_QTY'];
      // if($RSV_ITM_QTY > $ITM_AVAIL_QTY)
      // $response = 0;
    }
    else{
      $sql = "SELECT ITM_ID, ITEM_AVAIL_QTY FROM FLXY_ITEM WHERE ITM_STATUS = '1' AND ITM_ID = ".$data['RSV_ITM_ID']; 
      $responseArray = $this->Db->query($sql)->getResultArray();
      //$ITEM_AVAIL_QTY = $responseArray[0]['ITEM_AVAIL_QTY'];
      // if($RSV_ITM_QTY > $ITEM_AVAIL_QTY)
      // $response = 0;
      $response = 1;

    }
    
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


  public function checkReservationEndDate(string $str, string $fields, array $data)
  {
    /**
     *  Check End date cannot be greater than the Departure date 
     */

     if($data['FIXD_CHRG_FREQUENCY'] != 1 ){
        $FIXD_CHRG_END_DATE   =  date('Y-m-d',(strtotime($data['FIXD_CHRG_END_DATE'])));

        $sql = "SELECT * FROM FLXY_RESERVATION WHERE  ('".$FIXD_CHRG_END_DATE."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE) AND RESV_ID = ".$data['FIXD_CHRG_RESV_ID'];                 

        $response = $this->Db->query($sql)->getNumRows();

        if($response == 0)
        {
          return false;
        }
        else {
            return true;
        }  
      }

  }

  public function checkReservationStartDate(string $str, string $fields, array $data)
  {
    /**
     *  Check start date cannot be greater than the Departure date
     */

    //if($data['FIXD_CHRG_FREQUENCY'] != 1 ){
      $FIXD_CHRG_BEGIN_DATE = date('Y-m-d',(strtotime($data['FIXD_CHRG_BEGIN_DATE'])));     

      $sql = "SELECT * FROM FLXY_RESERVATION WHERE ('".$FIXD_CHRG_BEGIN_DATE."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE)  AND RESV_ID = ".$data['FIXD_CHRG_RESV_ID'];                 

      $response = $this->Db->query($sql)->getNumRows();

        if($response == 0)
        {
          return false;
        }
        else {
            return true;
        }  
    //}

  }


  public function compareFixedChargeDate(string $str, string $fields, array $data){
    
    $date_fields = array(array('start' => 'FIXD_CHRG_BEGIN_DATE', 'end' => 'FIXD_DEPARTURE_UP'));

    if($data['FIXD_CHRG_FREQUENCY'] != 1 ){
                       
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
}

public function checkReservationYearlyDate(string $str, string $fields, array $data)
  {
    /**
     *  Check start date cannot be greater than the Departure date
     */

    if($data['FIXD_CHRG_FREQUENCY'] == 6 ){
      $FIXD_CHRG_YEARLY = date('Y-m-d',(strtotime($data['FIXD_CHRG_YEARLY'])));     

      $sql = "SELECT * FROM FLXY_RESERVATION WHERE ('".$FIXD_CHRG_YEARLY."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE)  AND RESV_ID = ".$data['FIXD_CHRG_RESV_ID'];                 

      $response = $this->Db->query($sql)->getNumRows();

        if($response == 0)
        {
          return false;
        }
        else {
            return true;
        }  
    }

  }

  public function checkPackageStartDate(string $str, string $fields, array $data)
  {
    /**
     *  Check dates are between the reservation dates
     */
          $response = 0;
          $PACKAGE_BEGIN_DATE = date('Y-m-d',(strtotime($data['RSV_PCKG_BEGIN_DATE'])));           ; 
          $RESVSTART_DATE = date('Y-m-d',(strtotime($data['RESVSTART_DATE']))); 
          $RESVEND_DATE = date('Y-m-d',(strtotime($data['RESVEND_DATE'])));  
          
          if($PACKAGE_BEGIN_DATE >= $RESVSTART_DATE  && $PACKAGE_BEGIN_DATE <= $RESVEND_DATE ){
            $response = 1;
          }

          if($response == 0)
          {
            return false;
          }
          else {
              return true;
          }  
    }

    public function checkPackageEndDate(string $str, string $fields, array $data)
    {
      /**
       *  Check dates are between the reservation dates
       */
            $response = 0;
             
            $PACKAGE_END_DATE = date('Y-m-d',(strtotime($data['RSV_PCKG_END_DATE']))); 
            $RESVSTART_DATE = date('Y-m-d',(strtotime($data['RESVSTART_DATE']))); 
            $RESVEND_DATE = date('Y-m-d',(strtotime($data['RESVEND_DATE'])));  
            
            if($PACKAGE_END_DATE >= $RESVSTART_DATE && $PACKAGE_END_DATE <= $RESVEND_DATE){
              $response = 1;
            }
  
            if($response == 0)
            {
              return false;
            }
            else {
                return true;
            }  
      }
  


    public function packageAvailableCheck(string $str, string $fields, array $data)
    {
    /**
     *  Check dates are between the package dates
     */  
          $response = 0;
          $PACKAGE_BEGIN_DATE = date('Y-m-d',(strtotime($data['RSV_PCKG_BEGIN_DATE']))); 
          $PACKAGE_END_DATE = date('Y-m-d',(strtotime($data['RSV_PCKG_END_DATE'])));
          $RESVSTART_DATE = date('Y-m-d',(strtotime($data['RESVSTART_DATE'])));

            $Posting_Rhythm = $this->Db->query("SELECT PO_RH_ID       
            FROM FLXY_PACKAGE_CODE 
            WHERE PKG_CD_ID =".$data['PCKG_ID'])->getRow()->PO_RH_ID;

            if($Posting_Rhythm == 2)
            {
              $sql = "SELECT PKG_CD_ID FROM FLXY_PACKAGE_CODE_DETAIL WHERE ('".$PACKAGE_BEGIN_DATE."' BETWEEN PKG_CD_START_DT AND PKG_CD_END_DT) AND ('".$PACKAGE_END_DATE."' BETWEEN PKG_CD_START_DT AND PKG_CD_END_DT) AND PKG_CD_ID = ".$data['PCKG_ID']." AND PKG_CD_DT_STATUS = '1' AND PKG_CD_START_DT = '$PACKAGE_BEGIN_DATE'";
              $response = $this->Db->query($sql)->getNumRows();   
            }else{
              $sql = "SELECT PKG_CD_ID FROM FLXY_PACKAGE_CODE_DETAIL WHERE ('".$PACKAGE_BEGIN_DATE."' BETWEEN PKG_CD_START_DT AND PKG_CD_END_DT) AND ('".$PACKAGE_END_DATE."' BETWEEN PKG_CD_START_DT AND PKG_CD_END_DT) AND PKG_CD_ID = ".$data['PCKG_ID']." AND PKG_CD_DT_STATUS = '1'";
              $response = $this->Db->query($sql)->getNumRows();   
            }


          if($response == 0)
          {
            return false;
          }
          else {
            return true;
          }  
    }

    public function packageDateOverlapCheck(string $str, string $fields, array $data)
    {
      /**
       *  Check package dates are overlapped or not */
  
      $RSV_PCKG_BEGIN_DATE = date('Y-m-d',(strtotime($data['RSV_PCKG_BEGIN_DATE'])));
      $RSV_PCKG_END_DATE   =  date('Y-m-d',(strtotime($data['RSV_PCKG_END_DATE'])));
      $session_id = session_id();  
  
      if($data['RSV_PCKG_ID'] == '' && $data['PCKG_RESV_ID'] == '') 
        $sql = "SELECT * FROM FLXY_RESERVATION_PACKAGES WHERE PCKG_ID = '".$data['PCKG_ID']."' AND ('".$RSV_PCKG_BEGIN_DATE."' BETWEEN RSV_PCKG_BEGIN_DATE AND RSV_PCKG_END_DATE) AND ('".$RSV_PCKG_END_DATE."' BETWEEN RSV_PCKG_BEGIN_DATE AND RSV_PCKG_END_DATE) AND RSV_ID = 0 AND RSV_PCKG_SESSION_ID LIKE '".$session_id."'"; 
      else if($data['RSV_PCKG_ID'] == '' && $data['PCKG_RESV_ID'] != '')
        $sql = "SELECT * FROM FLXY_RESERVATION_PACKAGES WHERE RSV_ID = '".$data['PCKG_RESV_ID']."' AND PCKG_ID = '".$data['PCKG_ID']."' AND ('".$RSV_PCKG_BEGIN_DATE."' BETWEEN RSV_PCKG_BEGIN_DATE AND RSV_PCKG_END_DATE) AND ('".$RSV_PCKG_END_DATE."' BETWEEN RSV_PCKG_BEGIN_DATE AND RSV_PCKG_END_DATE)";
      else
      $sql = "SELECT * FROM FLXY_RESERVATION_PACKAGES WHERE RSV_ID = '".$data['PCKG_RESV_ID']."' AND PCKG_ID = '".$data['PCKG_ID']."' AND ('".$RSV_PCKG_BEGIN_DATE."' BETWEEN RSV_PCKG_BEGIN_DATE AND RSV_PCKG_END_DATE) AND ('".$RSV_PCKG_END_DATE."' BETWEEN RSV_PCKG_BEGIN_DATE AND RSV_PCKG_END_DATE) AND RSV_PCKG_ID != ".$data['RSV_PCKG_ID'];

       
       
      $response = $this->Db->query($sql)->getNumRows();
      return ($response==0 ? true : false); 
    }


    public function checkReservationTraceDate(string $str, string $fields, array $data)
    {
      /**
       *  Check dates are between the reservation dates
       */
            $response = 0;
             
            $TRACE_DATE = date('Y-m-d',(strtotime($data['RSV_TRACE_DATE']))); 
            $RESVSTART_DATE = date('Y-m-d',(strtotime($data['TRACE_ARRIVAL_DT']))); 
            $RESVEND_DATE = date('Y-m-d',(strtotime($data['TRACE_DEPARTURE_DT'])));  
            
            if($TRACE_DATE >= $RESVSTART_DATE && $TRACE_DATE <= $RESVEND_DATE){
              $response = 1;
            }
  
            if($response == 0)
            {
              return false;
            }
            else {
                return true;
            }  
      }
  

      public function Taskexists(string $str, string $fields, array $data)
      {
            /**
             *  Check task exists in this dates
             */
              $response = 0;              
              $HKTAO_ID          = $data['HKTAO_ID']; 
              $HKTAO_TASK_DATE   = date('Y-m-d',(strtotime($data['HKTAO_TASK_DATE']))); 
              $HKATO_TASK_CODE   = $data['HKATO_TASK_CODE'];  
              $HKATO_AUTO        = $data['HKATO_AUTO']; 
              
              if($HKTAO_ID != '')
              $cond = "HKTAO_ID != '".$HKTAO_ID."'";
              
              $sql = "SELECT * FROM FLXY_HK_TASKASSIGNMENT_OVERVIEW WHERE HKTAO_TASK_DATE = '".$HKTAO_TASK_DATE."' AND HKATO_TASK_CODE = '".$HKATO_TASK_CODE."' AND HKATO_AUTO = ".$HKATO_AUTO;
      
              $response = $this->Db->query($sql)->getNumRows();
              return ($response == 0 ? true : false); 
      }
  
      

    
    

  




}