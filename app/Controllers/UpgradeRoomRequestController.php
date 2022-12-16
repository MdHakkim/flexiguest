<?php

namespace App\Controllers;

use App\Controllers\Repositories\NotificationRepository;
use App\Controllers\Repositories\UpgradeRoomRequestRepository;
use App\Controllers\Repositories\UserRepository;
use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class UpgradeRoomRequestController extends BaseController
{

    use ResponseTrait;

    private $UpgradeRoomRequestRepository;
    private $NotificationRepository;
    private $UserRepository;

    public function __construct()
    {
        $this->UpgradeRoomRequestRepository = new UpgradeRoomRequestRepository();
        $this->NotificationRepository = new NotificationRepository();
        $this->UserRepository = new UserRepository();
    }

    public function upgradeRoomRequest()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/update_room_request', $data);
    }

    public function allRequests()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_UPGRADE_ROOM_REQUESTS left join FLXY_ROOM_TYPE on URR_ROOM_TYPE_ID = RM_TY_ID';
        $columns = 'URR_ID,URR_CUSTOMER_ID,URR_RESERVATION_ID,URR_ROOM_TYPE_ID,URR_STATUS,URR_CREATED_AT,RM_TY_DESC';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function submitRequest()
    {
        $user = $this->request->user;
        $data = json_decode(json_encode($this->request->getVar()), true);

        if (!$this->validate($this->UpgradeRoomRequestRepository->validationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->UpgradeRoomRequestRepository->createOrUpdateUpgradeRequest($user, $data);
        return $this->respond($result);
    }

    public function updateStatus()
    {
        $user = session('user');
        $data = json_decode(json_encode($this->request->getVar()), true);

        $request = $this->UpgradeRoomRequestRepository->getUpdgradeRequestById($data['URR_ID']);
        if (empty($request))
            return $this->respond(responseJson(202, true, ['msg' => 'Invalid request.']));

        $result = $this->UpgradeRoomRequestRepository->createOrUpdateUpgradeRequest($user, $data);

        if ($result['SUCCESS'] == 200) {
            $user_ids = $this->UserRepository->getUserIdsByCustomerIds([$request['URR_CUSTOMER_ID']]);

            $data = [
                'NOTIFICATION_TYPE' => 3,
                'NOTIFICATION_FROM_ID' => $user['USR_ID'],
                'NOTIFICATION_RESERVATION_ID' => json_encode(["{$request['URR_RESERVATION_ID']}"]),
                'NOTIFICATION_GUEST_ID' => json_encode(["{$request['URR_CUSTOMER_ID']}"]),
                'NOTIFICATION_TEXT' => "Your request to upgrade to '{$request['RM_TY_DESC']}' has been {$data['URR_STATUS']}.",
                'NOTIFICATION_DATE_TIME' => date('Y-m-d H:i:s'),
                'NOTIFICATION_READ_STATUS' => 0,
            ];

            $this->NotificationRepository->storeNotification($data, $user_ids);

            $registration_ids = $this->UserRepository->getRegistrationIds($user_ids);
            $this->NotificationRepository->sendNotificationToDevices($registration_ids, 'guest', $data['NOTIFICATION_TEXT']);
        }

        return $this->respond($result);
    }
}
