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
        $user_id = $this->request->user['USR_ID'];
        $data = (array) $this->request->getVar();
        $data['EV_CAR_IMAGES'] = $this->request->getFileMultiple('EV_CAR_IMAGES') ?? [];

        if(!$this->validate($this->EValetRepository->validationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->EValetRepository->submitEValetForm($user_id, $data);
        return $this->respond($result);
    }

    public function valetList()
    {
        $result = $this->EValetRepository->valetList();

        return $this->respond($result);
    }

    public function assignDriver()
    {
        $user_id = $this->request->user['USR_ID'];

        $data = (array) $this->request->getVar();
        $data['EV_STATUS'] = 'Driver Assigned';
        $data['EV_KEYS_COLLECTED'] = 1;
        $data['EV_UPDATE_BY'] = $user_id;
        $data['EV_UPDATE_AT'] = date('Y-m-d H:i:s');
        $data['EV_ASSIGNED_AT'] = date('Y-m-d H:i:s');


        $this->EValetRepository->updateEValet($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Driver assigned successfully.']));
    }

    public function parked()
    {
        $user_id = $this->request->user['USR_ID'];

        $data = (array) $this->request->getVar();
        $data['EV_STATUS'] = 'Parked';
        $data['EV_UPDATE_BY'] = $user_id;
        $data['EV_UPDATE_AT'] = date('Y-m-d H:i:s');

        $evalet = $this->EValetRepository->eValetById($data['EV_ID']);
        if(Empty($evalet))
            return $this->respond(responseJson(202, true, ['msg' => 'Invalid Evalet.']));

        if($evalet['EV_STATUS'] != 'Driver Assigned')
            return $this->respond(responseJson(202, true, ['msg' => 'Driver is not assigned yet.']));

        $this->EValetRepository->updateEValet($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Car Parked successfully.']));
    }

    public function guestCollected()
    {
        $user_id = $this->request->user['USR_ID'];

        $data = (array) $this->request->getVar();
        $data['EV_STATUS'] = 'Guest Collected';
        $data['EV_UPDATE_BY'] = $user_id;
        $data['EV_UPDATE_AT'] = date('Y-m-d H:i:s');

        $evalet = $this->EValetRepository->eValetById($data['EV_ID']);
        if(Empty($evalet))
            return $this->respond(responseJson(202, true, ['msg' => 'Invalid Evalet.']));

        if($evalet['EV_STATUS'] != 'Driver Assigned')
            return $this->respond(responseJson(202, true, ['msg' => 'Car status is not parked yet.']));

        $this->EValetRepository->updateEValet($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Car Parked successfully.']));
    }
}