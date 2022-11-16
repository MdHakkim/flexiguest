<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Guideline;
use App\Models\GuidelineFile;
use CodeIgniter\API\ResponseTrait;

class GuidelineRepository extends BaseController
{
    use ResponseTrait;

    private $Guideline;
    private $GuidelineFile;

    public function __construct()
    {
        $this->Guideline = new Guideline();
        $this->GuidelineFile = new GuidelineFile();
    }

    public function disableEnableGuideline($guideline_id)
    {
        $guideline = $this->Guideline->find($guideline_id);
        if(empty($guideline))
            return responseJson(404, true, ['msg' => 'Guideline not found.']);

        $msg = $guideline['GL_IS_ENABLED'] ? 'Disabled' : 'Enabled';
        $guideline['GL_IS_ENABLED'] = $guideline['GL_IS_ENABLED'] ? 0 : 1;
        $this->Guideline->save($guideline);

        return responseJson(200, false, ['msg' => $msg]);
    }

    public function allGuidelines()
    {
        $guidelines = $this->Guideline->where('GL_IS_ENABLED', 1)->orderBy('GL_SEQUENCE', 'desc')->findAll();
        
        foreach($guidelines as $index => $item) {
            $guidelines[$index]['GL_COVER_IMAGE'] = base_url($item['GL_COVER_IMAGE']);

            $guideline_files = $this->GuidelineFile->where('GLF_GUIDELINE_ID', $item['GL_ID'])->findAll();
            foreach($guideline_files as $j => $file){
                $guideline_files[$j]['GLF_FILE_URL'] = base_url($file['GLF_FILE_URL']);
            }

            $guidelines[$index]['GLF_GUIDELINE_FILES'] = $guideline_files;
        }

        return responseJson(200, false, 'Guidelines list', $guidelines);
    }

    public function deleteGuidelineFile($where_condition)
    {   
        return $this->GuidelineFile->where($where_condition)->delete();
    }
}