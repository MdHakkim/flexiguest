<?php

namespace App\Controllers;

use App\Libraries\GuidelineDataTable;
use App\Models\Guideline;
use App\Models\GuidelineFile;
use CodeIgniter\API\ResponseTrait;

class GuidelineController extends BaseController
{

    use ResponseTrait;

    private $Guideline;
    private $GuidelineFile;

    public function __construct()
    {
        $this->Guideline = new Guideline();
        $this->GuidelineFile = new GuidelineFile();
    }

    public function guideline()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/guideline', $data);
    }

    public function allGuidelines()
    {
        $mine = new GuidelineDataTable();
        $mine->generate_DatatTable();
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

        if ($this->request->getFileMultiple('files'))
            $rules = array_merge($rules, [
                'files.*' => [
                    'label' => 'Files',
                    'rules' => ['mime_in[files,image/png,image/jpg,image/jpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]', 'max_size[files, 2048]']
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
            $directory = "assets/Uploads/guidelines/cover_image/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson("500", true, "image not uploaded"));

            $data['cover_image'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        $data['title'] = trim($this->request->getPost('title'));
        $data['description'] = trim($this->request->getPost('description'));
        $data['body'] = trim($this->request->getPost('body'));

        $guideline_id = !empty($id)
            ? $this->Guideline->update($id, $data)
            : $this->Guideline->insert($data);
        
        if (!$guideline_id)
            return $this->respond(responseJson("-444", false, "db insert/update not successful", $guideline_id));

        if(!empty($id)) // when updating
            $guideline_id = $id;

        if ($this->request->getFileMultiple('files')) {

            foreach ($this->request->getFileMultiple('files') as $file) {
                $file_name = $file->getName();
                $directory = "assets/Uploads/guidelines/files/";

                $response = documentUpload($file, $file_name, $user_id, $directory);

                if ($response['SUCCESS'] != 200)
                    return $this->respond(responseJson("500", true, "file not uploaded"));

                $guideline_file['guideline_id'] = $guideline_id;
                $guideline_file['file_url'] = $directory . $response['RESPONSE']['OUTPUT'];
                $guideline_file['file_type'] = $file->getClientMimeType();
                $guideline_file['file_name'] = $file_name;

                $this->GuidelineFile->insert($guideline_file);
            }
        }
        
        if(empty($id))
            $msg = 'Guideline has been created.';
        else
            $msg = 'Guideline has been updated.';

        return $this->respond(responseJson("200", false, $msg));
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $guideline = $this->Guideline->where('id', $id)->first();

        if ($guideline) {
            $guideline['guideline_files'] = $this->GuidelineFile->where('guideline_id', $guideline['id'])->orderBy('file_type')->findAll();

            return $this->respond($guideline);
        }

        return $this->respond(responseJson(204, true, "guideline not found"));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $this->GuidelineFile->where('guideline_id', $id)->delete();

        $response = $this->Guideline->delete($id);
        if(!$response)
            return $this->respond(responseJson("-402", "record not deleted"));
        
        return $this->respond(responseJson("200", false, "record deleted", $response));
    }

    public function deleteOptionalFile()
    {
        $file_id = $this->request->getPost('file_id');

        $this->GuidelineFile->where('id', $file_id)->delete();

        return $this->respond(responseJson(200, false, "File deleted successfully.", []));
    }
}
