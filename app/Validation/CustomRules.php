<?php
namespace App\Validation;

class CustomRules{

  // Rule is to validate mobile number digits
  public function compareDate(){
    
    $startDate = strtotime($_POST['RT_CL_BEGIN_DT']);
    $endDate   = strtotime($_POST['RT_CL_END_DT']);

    if ($endDate >= $startDate || $endDate == '')
        return True;
    else {
        return False;
    }
    
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
}
