<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Controllers\Repositories\EValetRepository;
use CodeIgniter\API\ResponseTrait;

class EValetController extends BaseController
{
    use ResponseTrait;

    private $EValetRepository;

    public function __construct()
    {
        $this->EValetRepository = new EValetRepository();
    }

    public function submitForm()
    {        
        if(!$this->validate($this->EValetRepository->validationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $user_id = $this->request->user['USR_ID'];
        $data = (array) $this->request->getVar();
        $data['EV_CAR_IMAGES'] = $this->request->getFileMultiple('EV_CAR_IMAGES');

        
        $result = $this->EValetRepository->submitEValetForm($user_id, $data);
        return $this->respond($result);
    }

    public function valetList()
    {
        $result = $this->EValetRepository->valetList();

        return $this->respond($result);
    }
}