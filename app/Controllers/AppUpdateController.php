<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use App\Models\AppUpdate;
use CodeIgniter\API\ResponseTrait;

class AppUpdateController extends BaseController
{

    use ResponseTrait;

    private $AppUpdate;

    public function __construct()
    {
        $this->AppUpdate = new AppUpdate();
    }

    public function appUpdate()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/app_update', $data);
    }

    public function allAppUpdates()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_APP_UPDATES';
        $columns = 'AU_ID,AU_SEQUENCE,AU_TITLE,AU_COVER_IMAGE,AU_DESCRIPTION,AU_BODY,AU_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'AU_TITLE' => ['label' => 'title', 'rules' => 'required'],
            'AU_DESCRIPTION' => ['label' => 'description', 'rules' => 'required'],
            'AU_BODY' => ['label' => 'body', 'rules' => 'required'],
        ];

        if (empty($id) || $this->request->getFile('AU_COVER_IMAGE'))
            $rules = array_merge($rules, [
                'AU_COVER_IMAGE' => [
                    'label' => 'Cover Image',
                    'rules' => ['uploaded[AU_COVER_IMAGE]', 'mime_in[AU_COVER_IMAGE,image/png,image/jpg,image/jpeg]', 'max_size[AU_COVER_IMAGE,2048]']
                ],
            ]);

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $result = responseJson(403, true, $errors);

            return $this->respond($result);
        }

        $data = [];
        if ($this->request->getFile('AU_COVER_IMAGE')) {
            $image = $this->request->getFile('AU_COVER_IMAGE');
            $image_name = $image->getName();
            $directory = "assets/Uploads/news/cover_image/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson("500", true, ['msg' => "image not uploaded"]));

            $data['AU_COVER_IMAGE'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        $data['AU_TITLE'] = trim($this->request->getPost('AU_TITLE'));
        $data['AU_DESCRIPTION'] = trim($this->request->getPost('AU_DESCRIPTION'));
        $data['AU_BODY'] = trim($this->request->getPost('AU_BODY'));
        $data['AU_SEQUENCE'] = $this->request->getPost('AU_SEQUENCE');

        if (empty($id)) {
            $data['AU_CREATED_BY'] = $data['AU_UPDATED_BY'] = $user_id;
            $response = $this->AppUpdate->insert($data);
        } else {
            $data['AU_UPDATED_BY'] = $user_id;
            $response = $this->AppUpdate->update($id, $data);
        }

        if (!$response)
            return $this->respond(responseJson(500, false, ['msg' => "db insert/update not successful"], $response));

        if (empty($id))
            $msg = 'App Update has been created successflly.';
        else
            $msg = 'App Update has been updated successflly.';

        return $this->respond(responseJson("200", false, ['msg' => $msg]));
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $app_update = $this->AppUpdate->where('AU_ID', $id)->first();

        if ($app_update)
            return $this->respond($app_update);

        return $this->respond(responseJson(204, true, ['msg' => "App Update not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->AppUpdate->delete($id);

        $result = $return
            ? responseJson(200, false, ['msg' => "App update deleted successfully."], $return)
            : responseJson(500, true, ['msg' => "record not deleted"]);

        return $this->respond($result);
    }
}
