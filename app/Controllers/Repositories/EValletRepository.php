<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\EVallet;
use App\Models\EValletFile;
use CodeIgniter\API\ResponseTrait;

class EValletRepository extends BaseController
{
    use ResponseTrait;

    private $EVallet;
    private $EValletFile;

    public function __construct()
    {
        $this->EVallet = new EVallet();
        $this->EValletFile = new EValletFile();
    }

    public function validationRules()
    {
        return [
            'EV_GUEST_TYPE' => ['label' => 'guest type', 'rules' => 'required'], // Walk-In | InHouse Guest
            'EV_CAR_PLATE_NUMBER' => ['label' => 'plate number', 'rules' => 'required'],
            'EV_CAR_MAKE' => ['label' => 'car make', 'rules' => 'required'],
            'EV_CAR_MODEL' => ['label' => 'car model', 'rules' => 'required'],
            'EV_CAR_IMAGES' => [
                'label' => 'car images',
                'rules' => ['uploaded[EV_CAR_IMAGES]', 'mime_in[EV_CAR_IMAGES,image/png,image/jpg,image/jpeg]', 'max_size[EV_CAR_IMAGES,2048]']
            ],
        ];
    }

    public function submitEValletForm($user_id, $data)
    {
        $images = $data['EV_CAR_IMAGES'];

        $data['EV_CREATED_BY'] = $data['EV_UPDATED_BY'] = $user_id;
        unset($data['EV_CAR_IMAGES']);

        $evallet_id = $this->EVallet->insert($data);

        foreach ($images as $image) {
            $image_name = $image->getName();
            $directory = "assets/Uploads/evallet/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return responseJson(500, true, ['msg' => "file not uploaded"]);

            $evallet_image['EVI_EVALLET_ID'] = $evallet_id;
            $evallet_image['EVI_FILE_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
            $evallet_image['EVI_FILE_TYPE'] = $image->getClientMimeType();
            $evallet_image['EVI_FILE_NAME'] = $image_name;
            $evallet_image['EVI_CREATED_BY'] = $evallet_image['EVI_UPDATED_BY'] = $user_id;

            $this->EValletFile->insert($evallet_image);
        }

        return responseJson(200, false, ['msg' => 'created successfully.']);
    }

    public function valletList()
    {
        $vallet_list = $this->EVallet->findAll();
        foreach ($vallet_list as $index => $vallet) {
            $images = $this->EValletFile->where('EVI_EVALLET_ID', $vallet['EV_ID'])->findAll();
            foreach($images as $i => $image) {
                $images[$i]['EVI_FILE_URL'] = base_url($image['EVI_FILE_URL']);
            }

            $vallet_list[$index]['EV_CAR_IMAGES'] = $images;
        }

        return responseJson(200, false, ['msg' => 'vallet list'], $vallet_list);
    }
}
