<?php

namespace App\Controllers;

use App\Controllers\Repositories\AlertRepository;
use App\Controllers\Repositories\DepartmentRepository;
use App\Controllers\Repositories\UserRepository;
use CodeIgniter\API\ResponseTrait;

class AlertController extends BaseController
{

    use ResponseTrait;

    private $AlertRepository;
    private $DepartmentRepository;
    private $UserRepository;

    public function __construct()
    {
        $this->AlertRepository = new AlertRepository();
        $this->DepartmentRepository = new DepartmentRepository();
        $this->UserRepository = new UserRepository();
    }

    public function alert()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['departments'] = $this->DepartmentRepository->allDepartments();

        return view('frontend/alert', $data);
    }

    public function allAlerts()
    {
        $this->AlertRepository->allAlerts();
    }

    public function store()
    {
        $user_id = session('USR_ID');

        if (!$this->validate($this->AlertRepository->validationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $data = $this->request->getPost();
        
        $result = $this->AlertRepository->storeAlert($user_id, $data);
        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $alert = $this->AlertRepository->alertById($id);

        if ($alert)
            return $this->respond($alert);

        return $this->respond(responseJson(404, true, ['msg' => "Alert not found"]));
    }

    public function delete()
    {
        $alert_id = $this->request->getPost('id');

        $result = $this->AlertRepository->deleteAlert($alert_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "Alert deleted successfully."])
            : responseJson(500, true, ['msg' => "Alert not deleted"]);

        return $this->respond($result);
    }
}
