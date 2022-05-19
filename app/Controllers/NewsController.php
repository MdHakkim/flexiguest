<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;
use App\Models\News;

class NewsController extends BaseController
{

    use ResponseTrait;

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
        $tableName = 'news';
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
            array_merge($rules, [
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
            ? $this->News->update($id, $data)
            : $this->News->insert($data);

        $result = $response
            ? responseJson("1", "0", $response, $response = '')
            : responseJson("-444", "db insert not successful", $response);

        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $news = $this->News->where('id', $id)->first();

        if ($news)
            return $this->respond($news);

        return $this->respond(responseJson(204, true, "news not found"));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->News->delete($id);
        $result = $return 
                    ? responseJson("200", false, "record deleted", $return)
                    : responseJson("-402", "record not deleted");
        
        return $this->respond($result);
    }
}
