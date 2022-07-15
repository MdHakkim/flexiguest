<?php

use Config\Services;


function checkPageAccess()
{
    /** Add Log entry */

    $Db = \Config\Database::connect();
    $session = \Config\Services::session();
    $request = \Config\Services::request();  
    
    $base_url = base_url();

    $current_url  = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];   
    
    $url = ltrim(str_replace($base_url,'',$current_url), '/');

    $sql = "SELECT * FROM FLXY_USER_ROLE_PERMISSION INNER JOIN FLXY_MENU ON MENU_ID = ROLE_MENU_ID WHERE ROLE_ID = '1' AND MENU_URL LIKE '$url'";
    
    $responseCount = $Db->query($sql)->getNumRows();
    return $responseCount;
    

}

