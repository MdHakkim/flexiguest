<?php

use Config\Services;

function documentUpload($file,$docname ,$userID,$folderPath)
    {
        

        // Renaming file before upload and just uplaod the file
		$temp = explode(".",$docname);
		$newfilename = round(microtime(true)).'-'.$userID . '.' . end($temp);

        if ($file->move($folderPath, $newfilename)) {

            return responseJson(200,true,"upload image successfully",$newfilename);
            
        } else {

            return responseJson(500,true,"Failed to upload image",[]);
    }               
}

?>