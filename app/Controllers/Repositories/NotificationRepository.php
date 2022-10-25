<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class NotificationRepository extends BaseController
{
	use ResponseTrait;

	private $Client;

	public function __construct()
	{
		$this->Client = \Config\Services::curlrequest();
	}

	public function sendNotification()
	{
		$data['method'] = 'POST';
		$data['url'] = 'https://fcm.googleapis.com/fcm/send';
		$data['body'] = [
			'headers' => [
				'Authorization' => 'key=AAAALuDGtcc:APA91bFFR2x43CDH4k0UIsI66cargBrQ0rfOHpqC0CZ_CsDCR26OQZBfULr_PFEj0rCONqrweqgUNKvV6SAey_iTFowwPaxyyEYedCAQ_b9USF4h4K9jqP9QNIUXTM8awL5T5a_OF8Ri',
				'Content-Type' => 'application/json'
			],
			'json' => [
				"priority" => "HIGH",
				"registration_ids" => ["fWrB40jNSG-hmSFLR0PQMx:APA91bFZBax526Ww53rQviLSvwDZykMxbUJ05rNi8nbFJtlwajHh8wsEXwbeqseI2WRCFzmpPhX6pOLHNvSB5668K_cxSc9I9cxl2ZsMb7N_s_DNg2Hz4mvool1WesTLR6xrrB6vbBX5"],
				"data" => [
					"title" => "my-data-item",
					"body" => "Portal",
					"badge" => "1",
					"content_available" => true,
					"screen" => "heelo"
				]
			]
		];

		try {
			$response = $this->Client->request($data['method'], $data['url'], $data['body']);
		} catch(\Exception $e) {
			$e->getMessage();
		}

		return true;
	}
}
