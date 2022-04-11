<?php



function responseJson($action=null,$error=null,$report=null,$output=null){
    $result["SUCCESS"] =$action;
    $result["RESPONSE"] = array("ERROR"=>$error,"REPORT_RES"=>$report,"OUTPUT"=>$output);
    return $result;
} 

function dump_value($value)
{
    echo "<pre>";
    print_r($value);
    echo "</pre>";
}
?>