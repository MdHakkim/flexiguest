<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\CurlRequestLibrary;
use App\Models\Notification;
use App\Models\NotificationUser;
use CodeIgniter\API\ResponseTrait;

class NotificationRepository extends BaseController
{
	use ResponseTrait;

	private $Notification;
	private $NotificationUser;
	private $CurlRequestLibrary;

	public function __construct()
	{
		$this->Notification = new Notification();
		$this->NotificationUser = new NotificationUser();
		$this->CurlRequestLibrary = new CurlRequestLibrary();
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

	public function getUserNotifications($user)
	{
		if ($user['USR_ROLE_ID'] == 2)
			$where_condition = "NOTIFICATION_GUEST_ID like '%\"{$user['USR_CUST_ID']}\"%'";
		else
			$where_condition = "NOTIFICATION_TO_ID like '%\"{$user['USR_ID']}\"%'";

		return $this->Notification
			->join('FLXY_NOTIFICATION_USERS', "NOTIFICATION_ID = NU_NOTIFICATION_ID AND NU_USER_ID = {$user['USR_ID']}", 'left')
			->where($where_condition)
			->orderBy('NOTIFICATION_ID', 'desc')
			->findAll();
	}

	public function userReadNotifications($user, $notification_ids)
	{
		foreach ($notification_ids as $notification_id) {
			$this->NotificationUser->insert([
				'NU_USER_ID' => $user['USR_ID'],
				'NU_NOTIFICATION_ID' => $notification_id,
				'NU_READ_STATUS' => 1,
				'NU_CREATED_BY' => $user['USR_ID'],
				'NU_UPDATED_BY' => $user['USR_ID'],
			]);
		}

		return true;
	}
}
