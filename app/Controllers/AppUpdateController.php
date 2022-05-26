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
        $tableName = 'app_updates';
        $columns = 'id,title,cover_image,description,body,created_at';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');
        
        $rules = [
            'title' => ['required'],
            'description' => ['required'],
            'body' => ['required'],
        ];

        if (empty($id) || $this->request->getFile('cover_image'))
            $rules = array_merge($rules, [
                'cover_image' => [
                    'label' => 'Cover Image',
                    'rules' => ['uploaded[cover_image]', 'mime_in[cover_image,image/png,image/jpg,image/jpeg]', 'max_size[cover_image,2048]']
                ],
            ]);

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $result = responseJson("-402", $errors);
            
            return $this->respond($result);
        }

        $data = [];
        if ($this->request->getFile('cover_image')) {
            $image = $this->request->getFile('cover_image');
            $image_name = $image->getName();
            $directory = "assets/Uploads/news/cover_image/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson("500", true, "image not uploaded"));

            $data['cover_image'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        $data['title'] = trim($this->request->getPost('title'));
        $data['description'] = trim($this->request->getPost('description'));
        $data['body'] = trim($this->request->getPost('body'));

        $response = !empty($id)
            ? $this->AppUpdate->update($id, $data)
            : $this->AppUpdate->insert($data);


        if (!$response)
            return $this->respond(responseJson("-444", false, "db insert/update not successful", $response));

        if(empty($id))
            $msg = 'App Update has been created successflly.';
        else
            $msg = 'App Update has been updated successflly.';

        return $this->respond(responseJson("200", false, $msg));
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $app_update = $this->AppUpdate->where('id', $id)->first();

        if ($app_update)
            return $this->respond($app_update);

        return $this->respond(responseJson(204, true, "App Update not found"));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->AppUpdate->delete($id);
        
        $result = $return 
                    ? responseJson("200", false, "App update deleted successfully.", $return)
                    : responseJson("-402", "record not deleted");
        
        return $this->respond($result);
    }
}
