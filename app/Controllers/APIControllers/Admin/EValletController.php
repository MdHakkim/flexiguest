<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Controllers\Repositories\EValletRepository;
use CodeIgniter\API\ResponseTrait;

class EValletController extends BaseController
{
    use ResponseTrait;

    private $EValletRepository;

    public function __construct()
    {
        $this->EValletRepository = new EValletRepository();
    }

    public function submitForm()
    {        
        if(!$this->validate($this->EValletRepository->validationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $user_id = $this->request->user['USR_ID'];
        $data = (array) $this->request->getVar();
        $data['EV_CAR_IMAGES'] = $this->request->getFileMultiple('EV_CAR_IMAGES');

        
        $result = $this->EValletRepository->submitEValletForm($user_id, $data);
        return $this->respond($result);
    }

    public function valletList()
    {
        $result = $this->EValletRepository->valletList();

        return $this->respond($result);
    }
}