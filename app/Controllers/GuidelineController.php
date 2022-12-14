<?php

namespace App\Controllers;

use App\Controllers\Repositories\GuidelineRepository;
use App\Libraries\DataTables\GuidelineDataTable;
use App\Models\Guideline;
use App\Models\GuidelineFile;
use CodeIgniter\API\ResponseTrait;

class GuidelineController extends BaseController
{

    use ResponseTrait;

    private $GuidelineRepository;
    private $Guideline;
    private $GuidelineFile;

    public function __construct()
    {
        $this->GuidelineRepository = new GuidelineRepository();
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
            'GL_TITLE' => ['label' => 'title', 'rules' => 'required'],
            'GL_DESCRIPTION' => ['label' => 'description', 'rules' => 'required'],
            'GL_BODY' => ['label' => 'body', 'rules' => 'required'],
        ];

        if (empty($id) || $this->request->getFile('GL_COVER_IMAGE'))
            $rules = array_merge($rules, [
                'GL_COVER_IMAGE' => [
                    'label' => 'Cover Image',
                    'rules' => ['uploaded[GL_COVER_IMAGE]', 'mime_in[GL_COVER_IMAGE,image/png,image/jpg,image/jpeg]', 'max_size[GL_COVER_IMAGE,5120]']
                ],
            ]);

        if ($this->request->getFileMultiple('GL_FILES'))
            $rules = array_merge($rules, [
                'GL_FILES.*' => [
                    'label' => 'Files',
                    'rules' => ['mime_in[GL_FILES,image/png,image/jpg,image/jpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]', 'max_size[GL_FILES,5120]']
                ],
            ]);

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $result = responseJson(403, true, $errors);

            return $this->respond($result);
        }

        $data = [];
        if ($this->request->getFile('GL_COVER_IMAGE')) {
            $image = $this->request->getFile('GL_COVER_IMAGE');
            $image_name = $image->getName();
            $directory = "assets/Uploads/guidelines/cover_image/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson('500', true, ['msg' => 'image not uploaded']));

            $data['GL_COVER_IMAGE'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        $data['GL_TITLE'] = trim($this->request->getPost('GL_TITLE'));
        $data['GL_DESCRIPTION'] = trim($this->request->getPost('GL_DESCRIPTION'));
        $data['GL_BODY'] = trim($this->request->getPost('GL_BODY'));
        $data['GL_SEQUENCE'] = $this->request->getPost('GL_SEQUENCE');

        if (empty($id)) {
            $data['GL_CREATED_BY'] = $data['GL_UPDATED_BY'] = $user_id;
            $guideline_id = $this->Guideline->insert($data);
        } else {
            $data['GL_UPDATED_BY'] = $user_id;
            $guideline_id = $this->Guideline->update($id, $data);
        }

        if (!$guideline_id)
            return $this->respond(responseJson(500, true, ['msg' => "db insert/update not successfull"]));

        if (!empty($id)) // when updating
            $guideline_id = $id;

        if ($this->request->getFileMultiple('GL_FILES')) {
            if (!empty($id)) // when updating
                $this->GuidelineRepository->deleteGuidelineFile("GLF_GUIDELINE_ID = $guideline_id");

            foreach ($this->request->getFileMultiple('GL_FILES') as $file) {
                $file_name = $file->getName();
                $directory = "assets/Uploads/guidelines/files/";

                $response = documentUpload($file, $file_name, $user_id, $directory);

                if ($response['SUCCESS'] != 200)
                    return $this->respond(responseJson("500", true, ['msg' => "file not uploaded"]));

                $guideline_file['GLF_GUIDELINE_ID'] = $guideline_id;
                $guideline_file['GLF_FILE_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
                $guideline_file['GLF_FILE_TYPE'] = $file->getClientMimeType();
                $guideline_file['GLF_FILE_NAME'] = $file_name;

                $this->GuidelineFile->insert($guideline_file);
            }
        }

        if (empty($id))
            $msg = 'Guideline has been created.';
        else
            $msg = 'Guideline has been updated.';

        return $this->respond(responseJson("200", false, ['msg' => $msg]));
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $guideline = $this->Guideline->where('GL_ID', $id)->first();

        if ($guideline) {
            $guideline['GUIDELINE_FILES'] = $this->GuidelineFile->where('GLF_GUIDELINE_ID', $guideline['GL_ID'])->orderBy('GLF_FILE_TYPE')->findAll();

            return $this->respond($guideline);
        }

        return $this->respond(responseJson(404, true, ['msg' => "guideline not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $this->GuidelineFile->where('GLF_GUIDELINE_ID', $id)->delete();

        $response = $this->Guideline->delete($id);
        if (!$response)
            return $this->respond(responseJson(500, true, ['msg' => "The Guideline has not been deleted"]));

        return $this->respond(responseJson(200, false, ['msg' => "The Guideline has been deleted successfully"]));
    }

    public function deleteOptionalFile()
    {
        $file_id = $this->request->getPost('file_id');

        $this->GuidelineFile->where('GLF_ID', $file_id)->delete();

        return $this->respond(responseJson(200, false, ['msg' => "File deleted successfully."]));
    }

    public function disableEnableGuideline()
    {
        $data = $this->request->getPost();
        $response = $this->GuidelineRepository->disableEnableGuideline($data['id']);

        return $this->respond($response);
    }
}
