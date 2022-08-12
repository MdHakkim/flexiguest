<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use App\Models\BrandingLogo;
use CodeIgniter\API\ResponseTrait;

class BrandingLogoController extends BaseController
{

    use ResponseTrait;

    private $BrandingLogo;

    public function __construct()
    {
        $this->BrandingLogo = new BrandingLogo();
    }

    public function brandingLogo()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/branding_logo', $data);
    }

    public function allBrandingLogo()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_BRANDING_LOGO';
        $columns = 'BL_ID,BL_URL,BL_UPDATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $rules = [
            'BL_LOGO' => [
                'label' => 'Logo',
                'rules' => ['uploaded[BL_LOGO]', 'mime_in[BL_LOGO,image/png,image/jpg,image/jpeg,image/webp]', 'max_size[BL_LOGO, 2048]']
            ],
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->respond(responseJson(403, true, $errors));
        }

        $data = [];
        if ($this->request->getFile('BL_LOGO')) {
            $file = $this->request->getFile('BL_LOGO');

            $file_name = $file->getName();
            $directory = "assets/img/logo/";
        
            $response = documentUpload($file, $file_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson(500, true, ['msg' => "unable to upload."]));

            $data['BL_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
            $data['BL_CREATED_BY'] = $user_id;
            $data['BL_UPDATED_BY'] = $user_id;
        }

        $this->BrandingLogo->where('BL_ID = BL_ID')->delete();
        $this->BrandingLogo->insert($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Branding logo updated.'], $response = ''));
    }
}
