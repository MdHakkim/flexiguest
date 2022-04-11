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

// path joiner
function joinPaths() {
    $args = func_get_args();
    $paths = array();
    foreach ($args as $arg) {
        $paths = array_merge($paths, (array)$arg);
    }

    $paths = array_map(create_function('$p', 'return trim($p, "/");'), $paths);
    $paths = array_filter($paths);
    return join('/', $paths);
}

?>