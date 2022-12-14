<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;
use App\Models\News;

class NewsController extends BaseController
{

    use ResponseTrait;

    private $News;

    public function __construct()
    {
        $this->News = new News();
    }

    public function news()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/news', $data);
    }

    public function allNews()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_NEWS';
        $columns = 'NS_ID,NS_SEQUENCE,NS_TITLE,NS_COVER_IMAGE,NS_DESCRIPTION,NS_BODY,NS_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');
        
        $rules = [
            'NS_TITLE' => ['label' => 'title', 'rules' => 'required'],
            'NS_DESCRIPTION' => ['label' => 'decription', 'rules' => 'required'],
            'NS_BODY' => ['label' => 'body', 'rules' => 'required'],
        ];

        if (empty($id) || $this->request->getFile('NS_COVER_IMAGE'))
            $rules = array_merge($rules, [
                'NS_COVER_IMAGE' => [
                    'label' => 'Cover Image',
                    'rules' => ['uploaded[NS_COVER_IMAGE]', 'mime_in[NS_COVER_IMAGE,image/png,image/jpg,image/jpeg]', 'max_size[NS_COVER_IMAGE,5120]']
                ],
            ]);

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $result = responseJson(403, true, $errors);
            
            return $this->respond($result);
        }

        $data = [];
        if ($this->request->getFile('NS_COVER_IMAGE')) {
            $image = $this->request->getFile('NS_COVER_IMAGE');
            $image_name = $image->getName();
            $directory = "assets/Uploads/news/cover_image/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson("500", true, "image not uploaded"));

            $data['NS_COVER_IMAGE'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        $data['NS_TITLE'] = trim($this->request->getPost('NS_TITLE'));
        $data['NS_DESCRIPTION'] = trim($this->request->getPost('NS_DESCRIPTION'));
        $data['NS_BODY'] = trim($this->request->getPost('NS_BODY'));
        $data['NS_SEQUENCE'] = $this->request->getPost('NS_SEQUENCE');

        if(empty($id)){
            $data['NS_CREATED_BY'] = $data['NS_UPDATED_BY'] = $user_id;
            $response = $this->News->insert($data);
        }
        else{
            $data['NS_UPDATED_BY'] = $user_id;
            $response = $this->News->update($id, $data);
        }

        $result = $response
            ? responseJson(200, "0", $response, $response = '')
            : responseJson(500, "db insert not successful", $response);

        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $news = $this->News->where('NS_ID', $id)->first();

        if ($news)
            return $this->respond($news);

        return $this->respond(responseJson(204, true, "news not found"));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->News->delete($id);
        $result = $return 
                    ? responseJson(200, false, "record deleted", $return)
                    : responseJson(500, true, "record not deleted");
        
        return $this->respond($result);
    }
}
