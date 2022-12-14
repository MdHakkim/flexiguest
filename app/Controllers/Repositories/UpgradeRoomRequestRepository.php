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

        if(isWeb())
            $rules['URR_ID'] = ['label' => 'request id', 'rules' => 'required'];

        return $rules;
    }

    public function createOrUpdateUpgradeRequest($user, $data)
    {
        $data['URR_CUSTOMER_ID'] = $user['USR_CUST_ID'];

        $this->UpgradeRoomRequest->save($data);
        
        $msg = empty($data['URR_ID']) ? 'Request submitted successfully.' : 'Request updated successfully.';
        return responseJson(200, false, ['msg' => $msg]);
    }
}