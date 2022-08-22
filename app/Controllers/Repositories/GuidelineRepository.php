<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Guideline;
use CodeIgniter\API\ResponseTrait;

class GuidelineRepository extends BaseController
{
    use ResponseTrait;

    private $Guideline;

    public function __construct()
    {
        $this->Guideline = new Guideline();
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
}