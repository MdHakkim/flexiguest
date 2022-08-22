<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Controllers\Repositories\GuidelineRepository;
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
        $response = $this->GuidelineRepository->allGuidelines();
        return $this->respond($response);
    }
}