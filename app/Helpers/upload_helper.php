<?php
function documentUpload($file, $docname, $userID, $folderPath, $micro = 0)
{


    // Renaming file before upload and just uplaod the file
    $temp = explode(".", $docname);
    $name_without_ext = implode(' ', array_slice($temp, 0, -1));
    if ($micro == 1) {
        $newfilename =  $userID . '-' . $name_without_ext . '.' . end($temp);
    } else {

        $newfilename = round(microtime(true)) . '-' . $userID . '-' . $name_without_ext . '.' . end($temp);
    }



    if ($file->move($folderPath, $newfilename)) {

        return responseJson(200, true, "upload image successfully", $newfilename);
    } else {

        return responseJson(500, true, "Failed to upload image", []);
    }
}

function newFileName($file, $user_id)
{
    return $file->getRandomName() . "-" . $user_id . '-' . $file->getName();
}

function getOriginalFileName($file_name)
{
    return preg_replace("/[A-Z\d]+-\d+-/i", "", $file_name);
}

function getFileType($type)
{
    $type = strtolower($type);

    if($type == 'jpg' || $type == 'jpeg' || $type == 'png')
        return 'image';

    else if($type == 'pdf')
        return 'pdf';

    else
        return 'document';
}