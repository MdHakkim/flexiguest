<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\CurlRequestLibrary;
use App\Models\Notification;
use App\Models\NotificationTrail;
use App\Models\ReservationTrace;
use App\Models\UserDevice;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class NotificationRepository extends BaseController
{
	use ResponseTrait;

	private $Notification;
	private $NotificationTrail;
	private $ReservationTrace;
	private $CurlRequestLibrary;
	private $UserDevice;
	private $UserModel;

	public function __construct()
	{
		$this->Notification = new Notification();
		$this->NotificationTrail = new NotificationTrail();
		$this->ReservationTrace = new ReservationTrace();
		$this->CurlRequestLibrary = new CurlRequestLibrary();
		$this->UserDevice = new UserDevice();
		$this->UserModel = new UserModel();
	}

	public function sendNotification($data, $type = 'admin')
	{
		$admin_auth_key = 'AAAADhXeOx4:APA91bGJqz4L6-oPvb3C2g__GHNcMNOEdkQxKE1lhU9BFefkqeZQU19nLlSY2GYXA9m68_YvDLFR6iGtbVrl4Xsx57NUVwBwFToR8_QPaHzgzQrssVY2AHFp0ziXb9IenG--y8xWcXrf';
		$guest_auth_key = 'AAAALuDGtcc:APA91bFFR2x43CDH4k0UIsI66cargBrQ0rfOHpqC0CZ_CsDCR26OQZBfULr_PFEj0rCONqrweqgUNKvV6SAey_iTFowwPaxyyEYedCAQ_b9USF4h4K9jqP9QNIUXTM8awL5T5a_OF8Ri';

		$auth_key = $admin_auth_key;
		if ($type == 'guest')
			$auth_key = $guest_auth_key;

		$data['method'] = 'POST';
		$data['url'] = 'https://fcm.googleapis.com/fcm/send';
		$data['body'] = [
			'headers' => [
				'Authorization' => "key=$auth_key",
				'Content-Type' => 'application/json'
			],
			'json' => [
				'priority' => 'HIGH',
				'registration_ids' => $data['registration_ids'],
				'data' => [
					'badge' => '1',
					'content_available' => true,

					'title' => $data['title'],
					'body' => $data['body'],
					'screen' => $data['screen'],
				]
			]
		];

		return $this->CurlRequestLibrary->makeRequest($data);
	}

	// public function storeNotificationUsers($user, $user_ids, $notification_id)
	// {
	// 	if (!empty($user_ids)) {
	// 		foreach ($user_ids as $user_id) {
	// 			$this->NotificationUser->insert([
	// 				'NU_USER_ID' => $user_id,
	// 				'NU_NOTIFICATION_ID' => $notification_id,
	// 				'NU_READ_STATUS' => 0,
	// 				'NU_CREATED_BY' => $user['USR_ID'],
	// 				'NU_UPDATED_BY' => $user['USR_ID'],
	// 			]);
	// 		}
	// 	}

	// 	return true;
	// }

	public function getUserNotifications($user)
	{
		return $this->Notification
			->join('FLXY_NOTIFICATION_TRAIL', "NOTIFICATION_ID = NOTIF_TRAIL_NOTIFICATION_ID AND NOTIF_TRAIL_USER = {$user['USR_ID']}")
			->join('FLXY_RESERVATION_TRACES', 'NOTIFICATION_ID = RSV_TRACE_NOTIFICATION_ID', 'left')
			->orderBy('NOTIFICATION_ID', 'desc')
			->findAll();
	}

	public function userReadNotifications($user, $notification_ids)
	{
		if (empty($notification_ids)) { // mark all as read
			$this->NotificationTrail
				->set([
					'NOTIF_TRAIL_READ_STATUS' => 1,
				])
				->where(['NOTIF_TRAIL_USER' => $user['USR_ID']])
				->update();
		} else {
			if (!empty($notification_ids)) {
				foreach ($notification_ids as $notification_id) {
					$this->NotificationTrail
						->set([
							'NOTIF_TRAIL_READ_STATUS' => 1,
						])
						->where(['NOTIF_TRAIL_USER' => $user['USR_ID'], 'NOTIF_TRAIL_NOTIFICATION_ID' => $notification_id])
						->update();
				}
			}
		}

		return true;
	}

	public function unreadNotifications($user)
	{
		$unread_notifications = $this->NotificationTrail
			->where('NOTIF_TRAIL_USER', $user['USR_ID'])
			->where('NOTIF_TRAIL_READ_STATUS', 0)
			->findAll();

		return ['unread_notifications' => count($unread_notifications)];
	}

	public function traceResolved($user, $notification_id)
	{
		return $this->ReservationTrace
			->set(
				[
					'RSV_TRACE_RESOLVED_BY' => $user['USR_ID'],
					'RSV_TRACE_RESOLVED_ON' => date('Y-m-d H:i:s'),
					'RSV_TRACE_RESOLVED_TIME' => date('h:i:s A')
				]
			)
			->where('RSV_TRACE_NOTIFICATION_ID', $notification_id)
			->update();
	}

	public function notificationStatus($notification_id)
	{
		return $this->NotificationTrail
			->join('FlXY_USERS', 'NOTIF_TRAIL_USER = USR_ID', 'left')
			->where('NOTIF_TRAIL_NOTIFICATION_ID', $notification_id)
			->findAll();
	}

	public function sendNotificationToDevices($registration_ids, $notification_type, $notification_text)
	{
		if (!empty($registration_ids)) {
			$response = $this->sendNotification([
				'registration_ids' => $registration_ids,
				'title' => 'Notification',
				'body' => $notification_text,
				'screen' => '',
			], $notification_type);

			error_log("Notification => " . json_encode($response));

			$remove_registration_ids = [];
			if (!empty($response['failure']) && $response['failure'] > 0) {
				foreach ($response['results'] as $index => $res) {
					if (!empty($res['error']) && ($res['error'] == 'NotRegistered' || $res['error'] == 'InvalidRegistration') && isset($registration_ids[$index])) {
						$remove_registration_ids[] = $registration_ids[$index];
					}
				}
			}

			error_log("remove_registration_ids => " . json_encode($remove_registration_ids));
			if (!empty($remove_registration_ids))
				$this->UserDevice->whereIn('UD_REGISTRATION_ID', $remove_registration_ids)->delete();
		}
	}

	public function storeNotificationTrail($data)
	{
		return $this->NotificationTrail->insert($data);
	}

	public function storeNotification($data, $user_ids)
	{
		$notification_id = $this->Notification->insert($data);

		foreach ($user_ids as $user_id) {
			$trail = [
				'NOTIF_TRAIL_NOTIFICATION_ID' => $notification_id,
				'NOTIF_TRAIL_USER' => $user_id,
				'NOTIF_TRAIL_READ_STATUS' => 0,
				'NOTIF_TRAIL_DATETIME' => date('Y-m-d H:i:s'),
				'NOTIFICATION_TRAIL_SEND' => 1
			];

			if ($data['NOTIFICATION_TYPE']) {
				$user = $this->UserModel->find($user_id);
				if (!empty($user))
					$trail['NOTIF_TRAIL_GUEST'] = $user['USR_CUST_ID'];
			}

			$this->storeNotificationTrail($trail);
		}

		return $notification_id;
	}
}
