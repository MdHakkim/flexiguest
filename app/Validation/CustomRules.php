<?php
namespace App\Validation;

class CustomRules{

  // Rule is to validate mobile number digits
  public function compareDate(){
    
    $date_fields = array( array('start' => 'RT_CL_BEGIN_DT', 'end' => 'RT_CL_END_DT'), 
                          array('start' => 'RT_CT_BEGIN_DT', 'end' => 'RT_CT_END_DT'), 
                          array('start' => 'RT_CD_BEGIN_SELL_DT', 'end' => 'RT_CT_END_DT'), 
                          array('start' => 'RT_CD_START_DT', 'end' => 'RT_CD_END_DT'), 
                          array('start' => 'rep_RT_CD_START_DT', 'end' => 'rep_RT_CD_END_DT'));

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

  public function compareDate2(){
    
    $startDate = strtotime($_POST['RT_CT_BEGIN_DT']);
    $endDate   = strtotime($_POST['RT_CT_END_DT']);

    if ($endDate >= $startDate || $endDate == '')
        return True;
    else {
        return False;
    }
    
  }

  public function compareDate3(){
    
    $startDate = strtotime($_POST['RT_CD_BEGIN_SELL_DT']);
    $endDate   = strtotime($_POST['RT_CD_END_SELL_DT']);

    if ($endDate >= $startDate || $endDate == '')
        return True;
    else {
        return False;
    }
    
  }
}