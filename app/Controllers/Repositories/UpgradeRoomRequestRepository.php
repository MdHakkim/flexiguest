<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\UpgradeRoomRequest;
use CodeIgniter\API\ResponseTrait;

class UpgradeRoomRequestRepository extends BaseController
{
    use ResponseTrait;

    private $UpgradeRoomRequest;

    public function __construct()
    {
        $this->UpgradeRoomRequest = new UpgradeRoomRequest();
    }

    public function validationRules()
    {
        $rules = [
            'URR_RESERVATION_ID' => ['label' => 'reservation', 'rules' => 'required'],
            'URR_ROOM_TYPE_ID' => ['label' => 'room type', 'rules' => 'required', 'errors' => ['required' => 'Please select a room type.']],
        ];

        if (isWeb())
            $rules['URR_ID'] = ['label' => 'request id', 'rules' => 'required'];

        return $rules;
    }

    public function getUpdgradeRequestById($id)
    {
        return $this->UpgradeRoomRequest->join('FLXY_ROOM_TYPE', 'URR_ROOM_TYPE_ID = RM_TY_ID', 'left')->find($id);
    }

    public function createOrUpdateUpgradeRequest($user, $data)
    {
        if ($user['USR_ROLE_ID'] == '2')
            $data['URR_CUSTOMER_ID'] = $user['USR_CUST_ID'];

        if (empty($data['URR_ID'])) {
            $data['URR_CREATED_BY'] = $data['URR_UPDATED_BY'] = $user['USR_ID'];
            $msg = 'Request submitted successfully.';
        } else {
            $request = $this->getUpdgradeRequestById($data['URR_ID']);
            if (empty($request))
                return responseJson(202, true, ['msg' => 'Invalid request.']);

            if ($request['URR_STATUS'] == 'Approved' || $request['URR_STATUS'] == 'Rejected')
                return responseJson(202, true, ['msg' => 'Status is already updated.']);

            $data['URR_UPDATED_BY'] = $user['USR_ID'];
            $msg = 'Request updated successfully.';
        }

        $this->UpgradeRoomRequest->save($data);

        return responseJson(200, false, ['msg' => $msg]);
    }
}
