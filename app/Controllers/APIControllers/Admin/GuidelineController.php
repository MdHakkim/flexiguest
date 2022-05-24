<?php

namespace App\Controllers\APIControllers\Admin;

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
        $guidelines = $this->Guideline->orderBy('id', 'desc')->findAll();
        
        foreach($guidelines as $index => $item){
            $guidelines[$index]['cover_image'] = base_url($item['cover_image']);

            $guideline_files = $this->GuidelineFile->where('guideline_id', $item['id'])->findAll();
            foreach($guideline_files as $j => $file){
                $guideline_files[$j]['file_url'] = base_url($file['file_url']);
            }

            $guidelines[$index]['guideline_files'] = $guideline_files;
        }

        $result = responseJson(200, false, 'Guidelines list', $guidelines);
        return $this->respond($result);
    }
}