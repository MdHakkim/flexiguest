<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Repositories\DepartmentRepository;
use App\Controllers\Repositories\EValetRepository;
use App\Controllers\Repositories\ReservationRepository;
use CodeIgniter\API\ResponseTrait;

class EValetController extends BaseController
{
    use ResponseTrait;

    private $ReservationRepository;
    private $EValetRepository;
    private $DepartmentRepository;

    public function __construct()
    {
        $this->ReservationRepository = new ReservationRepository();
        $this->EValetRepository = new EValetRepository();
        $this->DepartmentRepository = new DepartmentRepository();
    }

    public function submitForm()
    {
        $user_id = session('USR_ID') ?? $this->request->user['USR_ID'];
        $data = (array) $this->request->getVar();
        $data['EV_CAR_IMAGES'] = $this->request->getFileMultiple('EV_CAR_IMAGES') ?? [];

        if (!$this->validate($this->EValetRepository->validationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->EValetRepository->submitEValetForm($user_id, $data);
        return $this->respond($result);
    }

    public function valetList()
    {
        $user = $this->request->user;
        $result = $this->EValetRepository->valetList($user);

        return $this->respond($result);
    }

    public function assignDriver()
    {
        $user_id = $this->request->user['USR_ID'];

        $data = (array) $this->request->getVar();

        $evalet = $this->EValetRepository->eValetById($data['EV_ID']);
        if (empty($evalet))
            return $this->respond(responseJson(202, true, ['msg' => 'Invalid Evalet.']));

        $data['EV_UPDATE_BY'] = $user_id;
        $data['EV_UPDATE_AT'] = date('Y-m-d H:i:s');

        if ($evalet['EV_STATUS'] == 'Parked' || $evalet['EV_STATUS'] == 'Delivery Requested') {
            $data['EV_DELIVERY_DRIVER_ID'] = $data['DRIVER_ID'];
            $data['EV_STATUS'] = 'Delivery Assigned';
            $data['EV_DELIVERY_ASSIGNED_AT'] = date('Y-m-d H:i:s');
        } else {
            $data['EV_PARKING_DRIVER_ID'] = $data['DRIVER_ID'];
            $data['EV_STATUS'] = 'Parking Assigned';
            $data['EV_KEYS_COLLECTED'] = 1;
            $data['EV_PARKING_ASSIGNED_AT'] = date('Y-m-d H:i:s');
        }

        $this->EValetRepository->updateEValet($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Parking assigned successfully.']));
    }

    public function parked()
    {
        $user_id = $this->request->user['USR_ID'];

        $data = (array) $this->request->getVar();
        $data['EV_STATUS'] = 'Parked';
        $data['EV_UPDATE_BY'] = $user_id;
        $data['EV_UPDATE_AT'] = date('Y-m-d H:i:s');

        $evalet = $this->EValetRepository->eValetById($data['EV_ID']);
        if (empty($evalet))
            return $this->respond(responseJson(202, true, ['msg' => 'Invalid Evalet.']));

        if ($evalet['EV_STATUS'] != 'Parking Assigned')
            return $this->respond(responseJson(202, true, ['msg' => 'Driver is not assigned yet.']));

        $this->EValetRepository->updateEValet($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Car Parked successfully.']));
    }

    public function readyToCollect()
    {
        $user_id = $this->request->user['USR_ID'];

        $data = (array) $this->request->getVar();
        $data['EV_STATUS'] = 'Ready to Collect';
        $data['EV_UPDATE_BY'] = $user_id;
        $data['EV_UPDATE_AT'] = date('Y-m-d H:i:s');

        $evalet = $this->EValetRepository->eValetById($data['EV_ID']);
        if (empty($evalet))
            return $this->respond(responseJson(202, true, ['msg' => 'Invalid Evalet.']));

        if ($evalet['EV_STATUS'] != 'Delivery Assigned')
            return $this->respond(responseJson(202, true, ['msg' => 'Delivery Driver is not assigned yet.']));

        $this->EValetRepository->updateEValet($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Car is ready to collect.']));
    }

    public function guestCollected()
    {
        $user_id = $this->request->user['USR_ID'];

        $data = (array) $this->request->getVar();
        $data['EV_STATUS'] = 'Guest Collected';
        $data['EV_UPDATE_BY'] = $user_id;
        $data['EV_UPDATE_AT'] = date('Y-m-d H:i:s');

        $evalet = $this->EValetRepository->eValetById($data['EV_ID']);
        if (empty($evalet))
            return $this->respond(responseJson(202, true, ['msg' => 'Invalid Evalet.']));

        if ($evalet['EV_STATUS'] != 'Delivery Assigned' && $evalet['EV_STATUS'] != 'Ready to Collect')
            return $this->respond(responseJson(202, true, ['msg' => 'Delivery Driver is not assigned yet.']));

        $this->EValetRepository->updateEValet($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Car Parked successfully.']));
    }

    public function carDeliveryRequest()
    {
        $user_id = $this->request->user['USR_ID'];

        $data = (array) $this->request->getVar();

        $evalet = $this->EValetRepository->eValetById($data['EV_ID']);
        if (empty($evalet))
            return $this->respond(responseJson(202, true, ['msg' => 'Invalid Evalet.']));

        $data['EV_STATUS'] = 'Delivery Requested';
        $data['EV_UPDATE_BY'] = $user_id;
        $data['EV_UPDATE_AT'] = date('Y-m-d H:i:s');

        $this->EValetRepository->updateEValet($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Car delivery request submitted successfully.']));
    }

    public function evalet()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['departments'] = $this->DepartmentRepository->allDepartments();

        $where_condition = "RESV_STATUS = 'Due Pre Check-In' or RESV_STATUS = 'Pre Checked-In' or RESV_STATUS = 'Checked-In'";
        $data['reservations'] = $this->ReservationRepository->allReservations($where_condition);

        return view('frontend/evalet', $data);
    }

    public function allEValet()
    {
        $this->EValetRepository->allEValet();
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $evalet = $this->EValetRepository->eValetById($id);

        if ($evalet)
            return $this->respond($evalet);

        return $this->respond(responseJson(404, true, ['msg' => "EValet not found"]));
    }

    public function delete()
    {
        $valet_id = $this->request->getPost('id');

        $result = $this->EValetRepository->deleteEValetById($valet_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "E-Valet deleted successfully."])
            : responseJson(500, true, ['msg' => "E-Valet not deleted"]);

        return $this->respond($result);
    }

    public function qr($evalet_id)
    {
        return redirect()->to("https://chart.googleapis.com/chart?cht=qr&chl=$evalet_id&chs=260x260&chld=L|0");
    }
}
