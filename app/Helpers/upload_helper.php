<?php

use Config\Services;

function documentUpload($file,$docname ,$userID,$folderPath)
    {
        

        // Renaming file before upload and just uplaod the file
		$temp = explode(".",$docname);
        $name_without_ext = implode(' ', array_slice($temp, 0, -1));

		$newfilename = round(microtime(true)) . '-' . $userID . '-' . $name_without_ext . '.' . end($temp);

        if ($file->move($folderPath, $newfilename)) {

            return responseJson(200,true,"upload image successfully",$newfilename);
            
        } else {

            return responseJson(500,true,"Failed to upload image",[]);
    }               
}

?>