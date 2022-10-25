<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\CurlRequestLibrary;
use CodeIgniter\API\ResponseTrait;

class NotificationRepository extends BaseController
{
	use ResponseTrait;

	private $CurlRequestLibrary;

	public function __construct()
	{
		$this->CurlRequestLibrary = new CurlRequestLibrary();
	}

	public function sendNotification($user, $data)
	{
		$data['method'] = 'POST';
		$data['url'] = 'https://fcm.googleapis.com/fcm/send';
		$data['body'] = [
			'headers' => [
				'Authorization' => 'key=AAAALuDGtcc:APA91bFFR2x43CDH4k0UIsI66cargBrQ0rfOHpqC0CZ_CsDCR26OQZBfULr_PFEj0rCONqrweqgUNKvV6SAey_iTFowwPaxyyEYedCAQ_b9USF4h4K9jqP9QNIUXTM8awL5T5a_OF8Ri',
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
					'user_id' => $user['USR_ID']
				]
			]
		];

		return $this->CurlRequestLibrary->makeRequest($data);
	}
}
