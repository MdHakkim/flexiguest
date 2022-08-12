<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use App\Models\PropertyInfo;
use CodeIgniter\API\ResponseTrait;

class PropertyInfoController extends BaseController
{

    use ResponseTrait;

    private $PropertyInfo;

    public function __construct()
    {
        $this->PropertyInfo = new PropertyInfo();
    }

    public function propertyInfo()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/property_info', $data);
    }

    public function allPropertyInfo()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_PROPERTY_INFO';
        $columns = 'PI_ID,PI_NAME,PI_TYPE,PI_URL,PI_UPDATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $rules = [
            'PI_FILE' => [
                'label' => 'file',
                'rules' => ['uploaded[PI_FILE]', 'mime_in[PI_FILE,application/pdf]', 'max_size[PI_FILE, 5048]']
            ],
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->respond(responseJson(403, true, $errors));
        }

        $data = [];
        if ($this->request->getFile('PI_FILE')) {
            $file = $this->request->getFile('PI_FILE');

            $file_name = 'HospitalityBrochure.pdf';
            $directory = "assets/Uploads/handbook/";

            $folder_path = "{$_SERVER['DOCUMENT_ROOT']}/assets/Uploads/handbook/$file_name"; // for live
            // $folder_path = "{$_SERVER['DOCUMENT_ROOT']}/FlexiGuest/assets/Uploads/handbook/$file_name"; // for local

            if (file_exists($folder_path))
                unlink($folder_path);
        
            $response = documentUpload($file, $file_name, $user_id, $directory, 2); // store file with original name

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson(500, true, ['msg' => "file not uploaded"]));

            $data['PI_NAME'] = $file_name;
            $data['PI_TYPE'] = $file->getClientMimeType();
            $data['PI_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
            $data['PI_CREATED_BY'] = $user_id;
            $data['PI_UPDATED_BY'] = $user_id;
        }

        $this->PropertyInfo->where('PI_ID = PI_ID')->delete();
        $this->PropertyInfo->insert($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Property Info updated.'], $response = ''));
    }
}
