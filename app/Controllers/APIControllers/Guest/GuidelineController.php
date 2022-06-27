<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
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
        $guidelines = $this->Guideline->orderBy('GL_ID', 'desc')->findAll();
        
        foreach($guidelines as $index => $item){
            $guidelines[$index]['GL_COVER_IMAGE'] = base_url($item['GL_COVER_IMAGE']);

            $guideline_files = $this->GuidelineFile->where('GLF_GUIDELINE_ID', $item['GL_ID'])->findAll();
            foreach($guideline_files as $j => $file){
                $guideline_files[$j]['GLF_FILE_URL'] = base_url($file['GLF_FILE_URL']);
            }

            $guidelines[$index]['GLF_GUIDELINE_FILES'] = $guideline_files;
        }

        $result = responseJson(200, false, 'Guidelines list', $guidelines);
        return $this->respond($result);
    }
}