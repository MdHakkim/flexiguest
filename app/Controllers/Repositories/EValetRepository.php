<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\DataTables\EValetDataTable;
use App\Models\EValet;
use App\Models\EValetImage;
use CodeIgniter\API\ResponseTrait;

class EValetRepository extends BaseController
{
    use ResponseTrait;

    private $EValet;
    private $EValetImage;

    public function __construct()
    {
        $this->EValet = new EValet();
        $this->EValetImage = new EValetImage();
    }

    public function validationRules($data)
    {
        $rules = [
            'EV_GUEST_TYPE' => ['label' => 'guest type', 'rules' => 'required'], // Walk-In | InHouse Guest
            'EV_CAR_PLATE_NUMBER' => ['label' => 'plate number', 'rules' => 'required'],
            'EV_CAR_MAKE' => ['label' => 'car make', 'rules' => 'required'],
            'EV_CAR_MODEL' => ['label' => 'car model', 'rules' => 'required'],
        ];

        if(isWeb()) {
            $rules = array_merge($rules, [
                'EV_STATUS' => ['label' => 'status', 'rules' => 'required']
            ]);
        }

        if (!empty($data['EV_CAR_IMAGES']))
            $rules['EV_CAR_IMAGES'] = [
                'label' => 'car images',
                'rules' => ['uploaded[EV_CAR_IMAGES]', 'max_size[EV_CAR_IMAGES,5048]', 'mime_in[EV_CAR_IMAGES,image/png,image/jpg,image/jpeg]']
            ];

        if (!empty($data['EV_EMAIL']))
            $rules['EV_EMAIL'] = ['label' => 'email', 'rules' => "required|valid_email"];

        return $rules;
    }

    public function submitEValetForm($user_id, $data)
    {
        $evalet_id = $data['id'] ?? null;

        $images = $data['EV_CAR_IMAGES'];
        unset($data['EV_CAR_IMAGES']);

        foreach ($data as $index => $row) {
            if (empty($row))
                $data[$index] = null;
        }
        
        $data['EV_RESERVATION_ID'] = $data['EV_RESERVATION_ID'] ?? null;
        $data['EV_ROOM_ID'] = $data['EV_ROOM_ID'] ?? null;
        $data['EV_PARKING_DRIVER_ID'] = $data['EV_PARKING_DRIVER_ID'] ?? null;
        $data['EV_DELIVERY_DRIVER_ID'] = $data['EV_DELIVERY_DRIVER_ID'] ?? null;
        
        if (empty($evalet_id)) {
            $data['EV_CREATED_BY'] = $data['EV_UPDATED_BY'] = $user_id;
            $evalet_id = $this->EValet->insert($data);
        } else {
            $data['AL_UPDATED_BY'] = $user_id;
            $this->EValet->update($evalet_id, $data);
        }

        if(!empty($images))
            $this->deleteEValetImages("EVI_EVALET_ID = $evalet_id");

        foreach ($images as $image) {
            $image_name = $image->getName();
            $directory = "assets/Uploads/evalet/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return responseJson(500, true, ['msg' => "file not uploaded"]);

            $evalet_image['EVI_EVALET_ID'] = $evalet_id;
            $evalet_image['EVI_FILE_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
            $evalet_image['EVI_FILE_TYPE'] = $image->getClientMimeType();
            $evalet_image['EVI_FILE_NAME'] = $image_name;
            $evalet_image['EVI_CREATED_BY'] = $evalet_image['EVI_UPDATED_BY'] = $user_id;

            $this->EValetImage->insert($evalet_image);
        }

        return responseJson(200, false, ['msg' => 'created/updated successfully.']);
    }

    public function valetList($user)
    {
        $where_condtion = "1 = 1";
        if ($user['USR_ROLE_ID'] == '2') {
            $where_condtion = "EV_CUSTOMER_ID = {$user['USR_CUST_ID']}";
        } else if ($user['USR_ROLE_ID'] != '1' && $user['USR_ROLE_ID'] != '5') {
            $where_condtion = "EV_DRIVER_ID = {$user['USR_ID']}";
        }

        $valet_list = $this->EValet
            ->select("FLXY_EVALET.*, concat(fu.USR_FIRST_NAME, ' ', fu.USR_LAST_NAME) as EV_PARKING_DRIVER_NAME, 
                concat(users.USR_FIRST_NAME, ' ', users.USR_LAST_NAME) as EV_DELIVERY_DRIVER_NAME")
            ->join('FlXY_USERS as fu', 'EV_PARKING_DRIVER_ID = fu.USR_ID', 'left')
            ->join('FlXY_USERS as users', 'EV_DELIVERY_DRIVER_ID = users.USR_ID', 'left')
            ->where($where_condtion)
            ->orderBy('EV_ID', 'desc')->findAll();

        foreach ($valet_list as $index => $valet) {
            $images = $this->EValetImage->where('EVI_EVALET_ID', $valet['EV_ID'])->findAll();
            foreach ($images as $i => $image) {
                $images[$i]['EVI_FILE_URL'] = base_url($image['EVI_FILE_URL']);
            }

            $valet_list[$index]['EV_CAR_IMAGES'] = $images;
        }

        return responseJson(200, false, ['msg' => 'valet list'], $valet_list);
    }

    public function updateEValet($data)
    {
        return $this->EValet->save($data);
    }

    public function eValetById($evalet_id)
    {
        return $this->EValet->find($evalet_id);
    }

    public function allEValet()
    {
        $mine = new EValetDataTable();
        $tableName = 'FLXY_EVALET left join FlXY_USERS as pd on EV_PARKING_DRIVER_ID = pd.USR_ID left join FlXY_USERS as dd on EV_DELIVERY_DRIVER_ID = dd.USR_ID';
        $columns = "EV_ID|EV_PARKING_DRIVER_ID|EV_DELIVERY_DRIVER_ID|EV_CUSTOMER_ID|EV_RESERVATION_ID|EV_ROOM_ID|EV_GUEST_TYPE|EV_GUEST_NAME|EV_CONTACT_NUMBER|EV_EMAIL|EV_CAR_PLATE_NUMBER|EV_CAR_MAKE|EV_CAR_MODEL|EV_KEYS_COLLECTED|EV_STATUS|EV_PARKING_DETAILS|EV_PARKING_ASSIGNED_AT|EV_DELIVERY_ASSIGNED_AT|EV_CREATED_AT|concat(pd.USR_FIRST_NAME,' ',pd.USR_LAST_NAME)EV_PARKING_DRIVER_NAME|concat(dd.USR_FIRST_NAME,' ',dd.USR_LAST_NAME)EV_DELIVERY_DRIVER_NAME|(pd.USR_DEPARTMENT)EV_PARKING_DEPARTMENT_ID|(dd.USR_DEPARTMENT)EV_DELIVERY_DEPARTMENT_ID";

        $mine->generate_DatatTable($tableName, $columns, [], '|');
        exit;
    }

    public function deleteEValetImages($where_condtion)
    {
        return $this->EValetImage->where($where_condtion)->delete();
    }

    public function deleteEValetById($valet_id)
    {
        $this->deleteEValetImages("EVI_EVALET_ID = $valet_id");
        return $this->EValet->delete($valet_id);
    }
}
