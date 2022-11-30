<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Repositories\ReservationAssetRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\UserRepository;
use CodeIgniter\API\ResponseTrait;
use  App\Libraries\EmailLibrary;
use App\Models\City;
use App\Models\PropertyInfo;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\State;
use App\Models\VaccineDetail;

class APIController extends BaseController
{
    use ResponseTrait;

    private $DB;
    private $VaccineDetail;
    private $State;
    private $City;
    private $PropertyInfo;
    private $Room;
    private $Reservation;
    private $UserRepository;
    private $ReservationRepository;
    private $ReservationAssetRepository;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->VaccineDetail = new VaccineDetail();
        $this->State = new State();
        $this->City = new City();
        $this->PropertyInfo = new PropertyInfo();
        $this->Room = new Room();
        $this->Reservation = new Reservation();

        $this->UserRepository = new UserRepository();
        $this->ReservationRepository = new ReservationRepository();
        $this->ReservationAssetRepository = new ReservationAssetRepository();
    }

    // ----------- START API FOR FLEXI GUEST --------------//

    // REGISTRATION API
    public function registerAPI()
    {
        $rules = [
            "name" => "required|is_unique[FLXY_USERS.USR_NAME]",
            "email" => "required|valid_email|is_unique[FLXY_USERS.USR_EMAIL]",
            "phone_no" => "required",
            "password" => 'required|min_length[8]|max_length[255]|strongPassword[password]'
        ];

        $messages = [
            "name" => [
                "required" => "Name is required"
            ],
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in valid format",
                "is_unique" => "Email already Exist"
            ],
            "phone_no" => [
                "required" => "Phone Number is required"
            ],
            "password" => [
                "required" => "password is required",
                'strongPassword' => 'Password is not strong. It should contain at least one digit, one capital letter, one small letter, and one special character. (white spaces are not allowed).'
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $result = responseJson(409, true, $this->validator->getErrors());
            return $this->respond($result);
        } else {
            $email = $this->request->getVar("email");

            // check wheather the email is present in customer table
            $isCustomer_data = $this->DB->table('FLXY_CUSTOMER')->where('CUST_EMAIL', $email)->get()->getRowArray();

            if (empty($isCustomer_data)) {
                $result = responseJson(404, false, ["msg" => "Sorry , You are not Reserved any room."]);
                return $this->respond($result);
            }

            $data = [
                "USR_NAME" => $this->request->getVar("name"),
                "USR_FIRST_NAME" => $this->request->getVar("name"),
                "USR_LAST_NAME" => '',
                "USR_EMAIL" => $email,
                "USR_PHONE" => $this->request->getVar("phone_no"),
                "USR_PASSWORD" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
                "USR_ROLE" => "Guest",
                "USR_ROLE_ID" => "2",
                "USR_CUST_ID" => $isCustomer_data['CUST_ID'],
                "USR_CREATED_DT" =>  date("d-M-Y"),
                "USR_UPDATED_DT" => date("d-M-Y")
            ];

            if ($this->DB->table('FLXY_USERS')->insert($data))
                $result = responseJson(200, false, ["msg" => "Successfully, user has been registered"]);
            else
                $result = responseJson(500, true, ["msg" => "Failed to create user"]);

            return $this->respond($result);
        }
    }

    // login API
    public function loginAPI()
    {

        $rules = [
            "email" => "required|valid_email|min_length[6]",
            "password" => "required",
        ];

        $messages = [
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format"
            ],
            "password" => [
                "required" => "password is required"
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $result = responseJson(403, true, $this->validator->getErrors());
            return $this->respond($result);
        } else {
            $sql = "SELECT u.*, fc.*, concat(fc.CUST_FIRST_NAME, ' ', fc.CUST_LAST_NAME) as NAME, ur.*  FROM FLXY_USERS as u LEFT JOIN FLXY_CUSTOMER fc ON fc.CUST_ID = u.USR_CUST_ID left join FLXY_USER_ROLE as ur on u.USR_ROLE_ID = ur.ROLE_ID WHERE u.USR_EMAIL = :email:";
            $param = ['email' => $this->request->getVar("email")];
            $userdata = $this->DB->query($sql, $param)->getRowArray();

            if (!empty($userdata)) {
                if (password_verify($this->request->getVar("password"), $userdata['USR_PASSWORD'])) {
                    if ($registration_id = $this->request->getVar('registration_id')) {
                        $userdata['UD_REGISTRATION_ID'] = $registration_id;
                        $this->UserRepository->storeUserDevice($userdata['USR_ID'], $registration_id);
                    }

                    // Token created
                    $token =   getSignedJWTForUser($userdata);
                    $result = responseJson(200, false, ["msg" => 'User logged In successfully'], ['token' => $token, 'user' => $userdata]);
                } else {
                    $result = responseJson(500, true, ["msg" => 'Incorrect details']);
                }
                return $this->respond($result);
            } else {
                $result = responseJson(404, false, ["msg" => 'User not found']);
                return $this->respond($result);
            }
        }
    }

    public function profileAPI()
    {
        $user = $this->request->user;

        $result = responseJson(200, false, ["msg" => "User details"], $user);
        return $this->respond($result);
    }

    // for Logout Just delete the token from session or anystorage
    public function logout()
    {
        if ($registration_id = $this->request->getVar('registration_id')) {
            $where_condition = "UD_REGISTRATION_ID = '$registration_id'";
            $this->UserRepository->removeUserDevice($where_condition);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'logout']));
    }

    // -----------------------------------------------------------------------CHECKIN API START -------------------------------------------//

    /*  ---------------------------------------------------

    Function : List all reservations with details
    METHOD: GET , 
    INPUT : Header Authorization- Token
    OUTPUT: Reservation details like Reservation_no,checkin _date,checkout_date,apartment_details,apartment no ,status ,name ,
            night, adult,childern count, document uploaded or not
    
     --------------------------------------------------- */
    public function listReservationsAPI($resID = null)
    {
        $cust_id = $this->request->user['USR_CUST_ID'];


        if ($resID) {
            $param = ['RESV_ID' => $resID];

            $sql = "SELECT  a.RESV_ID,a.RESV_NAME,a.RESV_CHILDREN,a.RESV_ADULTS,a.RESV_NIGHT,a.RESV_ARRIVAL_DT,a.RESV_DEPARTURE,a.RESV_STATUS, a.RESV_PAYMENT_STATUS, a.RESV_RATE, CONCAT_WS(' ', b.CUST_FIRST_NAME, b.CUST_LAST_NAME) as NAME ,d.RM_NO,d.RM_DESC FROM FLXY_RESERVATION a 
                            LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME 
                            LEFT JOIN FLXY_ROOM d ON d.RM_NO = a.RESV_ROOM  WHERE a.RESV_ID = :RESV_ID: order by a.RESV_ID desc";

            $data = $this->DB->query($sql, $param)->getRowArray();

            if (checkFileExists("assets/reservation-invoices/RES$resID-Invoice.pdf"))
                $data['INVOICE_URL'] = base_url("assets/reservation-invoices/RES$resID-Invoice.pdf");
        } else {
            $param = ['RESV_NAME' => $cust_id];
            $records = $this->DB->query("select ACCOMP_REF_RESV_ID from FLXY_ACCOMPANY_PROFILE where ACCOMP_CUST_ID = :RESV_NAME:", $param)->getResultArray();

            $reservation_ids = [];
            foreach ($records as $row)
                $reservation_ids[] = $row['ACCOMP_REF_RESV_ID'];
            $reservation_ids = implode(',', $reservation_ids);

            $where_condition = '';
            if (!empty($reservation_ids))
                $where_condition = "OR a.RESV_ID in ($reservation_ids)";

            $sql = "SELECT  a.RESV_ID,a.RESV_NAME,a.RESV_CHILDREN,a.RESV_ADULTS,a.RESV_NIGHT,a.RESV_ARRIVAL_DT,a.RESV_DEPARTURE,a.RESV_STATUS, a.RESV_PAYMENT_STATUS, a.RESV_RATE, CONCAT_WS(' ', b.CUST_FIRST_NAME, b.CUST_LAST_NAME) as NAME ,d.RM_NO,d.RM_DESC,b.CUST_EMAIL,b.CUST_MOBILE_CODE,b.CUST_MOBILE FROM FLXY_RESERVATION a 
                            LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME 
                            LEFT JOIN FLXY_ROOM d ON d.RM_NO = a.RESV_ROOM 
                            WHERE a.RESV_NAME = :RESV_NAME: $where_condition order by a.RESV_ID desc";
            $data = $this->DB->query($sql, $param)->getResultArray();

            foreach ($data as $index => $res) {
                if (checkFileExists("assets/reservation-invoices/RES{$res['RESV_ID']}-Invoice.pdf"))
                    $data[$index]['INVOICE_URL'] = base_url("assets/reservation-invoices/RES{$res['RESV_ID']}-Invoice.pdf");
            }
        }

        if (!empty($data))
            $result = responseJson(200, false, ["msg" => "Reservation fetched Successfully"], $data);
        else
            $result = responseJson(200, true, ["msg" => "No reservation found for this user"], []);

        return $this->respond($result);
    }

    /*  FUNCTION : To check the Docs like Proof and vaccination is uploaded or not and verified or not
        METHOD : GET , 
        INPUT  : Header Authorization- Token
        OUTPUT : list and details of the accompanying the persons   */
    public function checkDocDetails()
    {
        // for admin => will get customerId from parameters
        $CUST_ID = $this->request->getVar('customerId') ?? $this->request->user['USR_CUST_ID'];

        // an indicator to inform Docs are uploaded and verified.
        $sql = "SELECT concat(a.CUST_FIRST_NAME,' ',a.CUST_LAST_NAME)NAME,c.*,b.VACCINE_IS_VERIFY,c.DOC_IS_VERIFY FROM FLXY_CUSTOMER a
                    LEFT JOIN FLXY_VACCINE_DETAILS b ON b.CUST_ID = a.CUST_ID 
                    LEFT JOIN FLXY_DOCUMENTS c ON c.DOC_CUST_ID = a.CUST_ID WHERE DOC_FILE_TYPE='PROOF' AND a.CUST_ID = :CUST_ID:";
        $param = ['CUST_ID' => $CUST_ID];
        $data = $this->DB->query($sql, $param)->getResultArray();

        if (!empty($data))
            $result = responseJson(200, false, ["msg" => "Doc Details fetched Successfully"], $data);
        else
            $result = responseJson(200, false, ["msg" => "Fetching Failed"], $data);

        return $this->respond($result);
    }

    /*  FUNCTION : list of accompanying persons.
        METHOD : POST , 
        INPUT  : Header Authorization- Token
        OUTPUT : list and details of the accompanying the persons   */
    public function getGuestAccompanyProfiles()
    {
        $customer_id = $this->request->getVar('customer_id') ?? $this->request->user['USR_CUST_ID'];
        $reservation_id = $this->request->getVar('reservation_id');

        // an indicator to inform this is accompanying person
        $sql = "SELECT concat(fc.CUST_FIRST_NAME, ' ', fc.CUST_LAST_NAME) as name, 
                        fc.CUST_ID
                        FROM FLXY_CUSTOMER as fc
                        where CUST_ID = :customer_id:
                        group by fc.CUST_FIRST_NAME, fc.CUST_LAST_NAME, fc.CUST_ID";

        $param = ['customer_id' => $customer_id, 'reservation_id' => $reservation_id];
        $data = $this->DB->query($sql, $param)->getResultArray();
        if (!count($data))
            return $this->respond(responseJson(404, true, ["msg" => "Customer not found"]));

        $guest = $data[0];

        // DOC VERIFY
        $guest['DOC_IS_VERIFY'] = $guest['VACC_IS_VERIFY'] = 0;
        $guest['is_document_uploaded'] = 1;

        $sql = "select DOC_IS_VERIFY from FLXY_DOCUMENTS where DOC_CUST_ID = :CUST_ID: AND DOC_RESV_ID = :RESV_ID: AND DOC_FILE_TYPE = 'PROOF'";
        $res = $this->DB->query($sql, ['CUST_ID' => $customer_id, 'RESV_ID' => $reservation_id])->getResultArray();
        if (count($res))
            $guest['DOC_IS_VERIFY'] = $res[0]['DOC_IS_VERIFY'];
        else
            $guest['is_document_uploaded'] = 0;

        $sql = "select VACC_IS_VERIFY from FLXY_VACCINE_DETAILS where VACC_CUST_ID = :CUST_ID: AND VACC_RESV_ID = :RESV_ID:";
        $res = $this->DB->query($sql, ['CUST_ID' => $customer_id, 'RESV_ID' => $reservation_id])->getResultArray();
        if (count($res))
            $guest['VACC_IS_VERIFY'] = $res[0]['VACC_IS_VERIFY'];
        // DOC VERIFY

        $sql = "select * from FLXY_ACCOMPANY_PROFILE where ACCOMP_CUST_ID = :customer_id: and ACCOMP_REF_RESV_ID = :reservation_id:";
        $params = ['customer_id' => $customer_id, 'reservation_id' => $reservation_id];
        $data = $this->DB->query($sql, $params)->getResultArray();

        if (count($data)) // when user is not main guest
            return $this->respond(responseJson(200, false, ["msg" => "Accompany person"], $guest));

        $sql = "SELECT concat(fc.CUST_FIRST_NAME, ' ', fc.CUST_LAST_NAME) as name, 
                        fc.CUST_ID
                        FROM FLXY_CUSTOMER as fc
                        inner join FLXY_ACCOMPANY_PROFILE as fap on fc.CUST_ID = fap.ACCOMP_CUST_ID 
                        where fap.ACCOMP_REF_RESV_ID = :reservation_id:
                        group by fc.CUST_FIRST_NAME, fc.CUST_LAST_NAME, fc.CUST_ID";

        $param = ['reservation_id' => $reservation_id];
        $accompany_profiles = $this->DB->query($sql, $param)->getResultArray();

        foreach ($accompany_profiles as $index => $accompany_profile) {

            // DOC VERIFY
            $accompany_profiles[$index]['DOC_IS_VERIFY'] = $accompany_profiles[$index]['VACC_IS_VERIFY'] = 0;
            $accompany_profiles[$index]['is_document_uploaded'] = 1;

            $sql = "select DOC_IS_VERIFY from FLXY_DOCUMENTS where DOC_CUST_ID = :CUST_ID: AND DOC_RESV_ID = :RESV_ID: AND DOC_FILE_TYPE = 'PROOF'";
            $res = $this->DB->query($sql, ['CUST_ID' => $accompany_profile['CUST_ID'], 'RESV_ID' => $reservation_id])->getResultArray();
            if (count($res))
                $accompany_profiles[$index]['DOC_IS_VERIFY'] = $res[0]['DOC_IS_VERIFY'];
            else
                $accompany_profiles[$index]['is_document_uploaded'] = 0;

            $sql = "select VACC_IS_VERIFY from FLXY_VACCINE_DETAILS where VACC_CUST_ID = :CUST_ID: AND VACC_RESV_ID = :RESV_ID:";
            $res = $this->DB->query($sql, ['CUST_ID' => $accompany_profile['CUST_ID'], 'RESV_ID' => $reservation_id])->getResultArray();
            if (count($res))
                $accompany_profiles[$index]['VACC_IS_VERIFY'] = $res[0]['VACC_IS_VERIFY'];
            // DOC VERIFY
        }

        $guest['accompany_profiles'] = $accompany_profiles;

        if (!empty($guest['accompany_profiles']))
            $result = responseJson(200, false, ["msg" => "Accompany list for the reservation"], $guest);
        else
            $result = responseJson(200, false, ["msg" => "There is no accompany person"], $guest);

        return $this->respond($result);
    }

    /*  FUNCTION : send email to accompany person for uplaod document self 
        METHOD : POST , 
        INPUT  : Header Authorization- Token
        OUTPUT : list and details of the accompanying the persons   */
    public function requestSelfUpload()
    {
        $reservation_id = $this->request->getVar('reservation_id');
        $email = $this->request->getVar('email');
        $first_name = $this->request->getVar('first_name');
        $last_name = $this->request->getVar('last_name');

        // email sending to the accompany person
        $sql = "SELECT RESV_ID, RESV_NO, RESV_ARRIVAL_DT, RESV_DEPARTURE, RESV_NO_F_ROOM, RESV_FEATURE FROM FLXY_RESERVATION
                WHERE RESV_ID = :reservation_id:";
        $param = ['reservation_id' => $reservation_id];

        $reservationInfo = $this->DB->query($sql, $param)->getResultArray();
        if (!count($reservationInfo))
            return $this->respond(responseJson(404, true, ['msg' => 'Reservation not found.']));

        $reservationInfo[0]['CUST_EMAIL'] = $email;
        $reservationInfo[0]['CUST_FIRST_NAME'] = $first_name;
        $reservationInfo[0]['CUST_LAST_NAME'] = $last_name;

        $reservationInfo[0]['BASE_URL'] = base_url();


        $emailCall = new EmailLibrary();
        $emailResp = $emailCall->requestDocUploadEmail($reservationInfo, $email, $first_name . " " . $last_name);

        if ($emailResp)
            $result = responseJson(200, false, ["msg" => "Email send Successfully"]);
        else
            $result = responseJson(500, false, ["msg" => "Email sending failed"]);

        return $this->respond($result);
    }

    /*  Function : TO UPLOAD DOCUMENTS OF GUEST ON LOGIN
        METHOD: POST , 
        INPUT : Header Authorization- Token
        OUTPUT: PATH OF UPLAODED DOCUMENT */
    public function docUploadAPI()
    {
        $user_id = $this->request->user['USR_ID'];

        $customerID = $this->request->getVar('customerId'); // get from the frondend
        $resID = $this->request->getVar('reservationId');

        $validate = $this->validate([
            'customerId' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Error: Invalid Guest.'
                ]
            ],
            'reservationId' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Error: Invalid Reservation.'
                ]
            ],
            'files' => [
                'uploaded[files]',
                'mime_in[files,image/png,image/jpeg,image/jpg,application/pdf]',
                'max_size[files,50000]',
            ],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);
            return $this->respond($result);
        }

        $fileNames = '';
        $fileArry = $this->request->getFileMultiple('files');

        foreach ($fileArry as $key => $file) {
            if (!$file->isValid()) {
                return $this->respond(responseJson(500, true, ['msg' => "Please upload valid file. This file '{$file->getClientName()}' is not valid"]));
            }
        }

        $desc = $this->request->getVar("desc");
        foreach ($fileArry as $key => $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'assets/Uploads/userDocuments/proof', $newName);
                $comma = '';

                if (isset($fileArry[$key + 1]) && $fileArry[$key + 1]->isValid()) {
                    $comma = ',';
                }

                if ($newName)
                    $fileNames .= $newName . $comma;
            }
        }

        if (!empty($fileNames)) {
            // check wheather there is any entry with this user. 
            $doc_data = $this->DB->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_CUST_ID,DOC_FILE_PATH,DOC_RESV_ID')->where(['DOC_CUST_ID' => $customerID, 'DOC_FILE_TYPE' => 'PROOF', 'DOC_RESV_ID' => $resID])->get()->getRowArray();
            $data = [
                "DOC_CUST_ID" => $customerID,
                "DOC_IS_VERIFY" => 0,
                "DOC_FILE_PATH" => $fileNames,
                "DOC_FILE_TYPE" => 'PROOF',
                "DOC_RESV_ID" => $resID,
                "DOC_FILE_DESC" => $desc,
                "DOC_CREATE_UID" => $user_id,
                "DOC_CREATE_DT" => date("Y-m-d H:i:s"),
                "DOC_UPDATE_UID" => $user_id,
                "DOC_UPDATE_DT" => date("Y-m-d H:i:s")
            ];

            $ins = 0;
            $update_data = 0;

            if (empty($doc_data))
                $ins = $this->DB->table('FLXY_DOCUMENTS')->insert($data);
            else {
                unset($data['DOC_CREATE_UID']);
                unset($data['DOC_CREATE_DT']);

                $data['DOC_FILE_PATH'] = $doc_data['DOC_FILE_PATH'] . ',' . $fileNames;
                $update_data = $this->DB->table('FLXY_DOCUMENTS')->where(['DOC_CUST_ID' => $customerID, 'DOC_RESV_ID' => $resID])->update($data);
            }

            if ($ins || $update_data)
                $result = responseJson(200, false, ["msg" => "File uploaded successfully"], ["path" => $fileNames]);
            else
                $result = responseJson(500, true, ["msg" => "Failed to upload image"]);

            return $this->respond($result);
        }

        return $this->respond(responseJson(500, true, ["msg" => "something went wrong"]));
    }

    /*  FUNCTION : SAVE GUEST DETAILS FROM THE IMAGE UPLOADED.
        METHOD: POST , 
        INPUT : Header Authorization- Token
        OUTPUT : UPDATED STATUS.     */
    public function saveGuestDetails()
    {
        $user_id = $this->request->user['USR_ID'];
        // for admin => will get customerId from parameters
        $CUST_ID = $this->request->getVar('customerId') ?? $this->request->user['USR_CUST_ID'];

        $validate = $this->validate([
            'customerId' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Error: Invalid Guest.'
                ]
            ],
            'title' => 'required',
            'firstName' => 'required',
            //'email' => 'required|valid_email',
            'countryOfResidence' => 'required',
            'state' => 'required',
            'city' => 'required',
            'DOB' => 'required',
            'mobileCode' => 'required',
            'mobile' => 'required',
            'gender' => 'required',
            'docType' => 'required',
            'address1' => 'required',
            'nationality' => 'required',
            'docNumber' => 'required',
            'expiryDate' => 'required',
            'issueDate' => 'required',
            'city' => 'required',
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson("403", true, $validate);
            return $this->respond($result);
        }

        if (strtotime($this->request->getVar("expiryDate")) < strtotime($this->request->getVar("issueDate")) || strtotime($this->request->getVar("expiryDate")) <= strtotime(date("Y-m-d"))) {
            $validate = "Your Document is expired";
            $result = responseJson("403", true, $validate);
            return $this->respond($result);
        }

        $data = [
            "CUST_FIRST_NAME" => $this->request->getVar("firstName"),
            "CUST_MIDDLE_NAME" => $this->request->getVar("middleName"),
            "CUST_LAST_NAME" => $this->request->getVar("lastName"),
            "CUST_TITLE" => $this->request->getVar("title"),
            "CUST_DOC_TYPE" => $this->request->getVar("docType"),
            "CUST_DOC_NUMBER" => $this->request->getVar("docNumber"),
            "CUST_GENDER" => $this->request->getVar("gender"),
            "CUST_MOBILE_CODE" => $this->request->getVar("mobileCode"),
            "CUST_MOBILE" => $this->request->getVar("mobile"),
            "CUST_NATIONALITY" => $this->request->getVar("nationality"),
            "CUST_COR" => $this->request->getVar("countryOfResidence"),
            "CUST_COUNTRY" => $this->request->getVar("countryOfResidence"),
            "CUST_STATE" => $this->request->getVar("state"),
            "CUST_STATE_ID" => $this->request->getVar("state_id"),
            "CUST_CITY" => $this->request->getVar("city"),
            "CUST_DOB" => date("d-M-Y", strtotime($this->request->getVar("DOB"))),
            "CUST_DOC_EXPIRY" => date("d-M-Y", strtotime($this->request->getVar("expiryDate"))),
            "CUST_DOC_ISSUE" => date("d-M-Y", strtotime($this->request->getVar("issueDate"))),
            "CUST_PHONE" => $this->request->getVar("phone"),
            "CUST_EMAIL" => $this->request->getVar("email"),
            "CUST_ADDRESS_1" => $this->request->getVar("address1"),
            "CUST_ADDRESS_2" => $this->request->getVar("address2"),
            "CUST_UPDATE_UID" => $user_id,
            "CUST_UPDATE_DT" => date("d-M-Y")
        ];

        $update = $this->DB->table('FLXY_CUSTOMER')->where('CUST_ID', $CUST_ID)->update($data);
        if ($update)
            $result = responseJson(200, true, ["msg" => "updated the guest details"]);
        else
            $result = responseJson(500, true, ["msg" => "updation Failed"]);

        return $this->respond($result);
    }

    /*  FUNCTION : FETCH ALL DETAILS OF GUEST
        METHOD: GET 
        INPUT : Header Authorization- Token
        OUTPUT : CUSTOMER_DATA  */
    public function FetchSavedDocDetails()
    {
        $CUST_ID = $this->request->getVar('customerId');
        $RESV_ID = $this->request->getVar('reservationId');

        $filePath = base_url('assets/Uploads/userDocuments/proof');

        $param = ['CUST_ID' => $CUST_ID, 'RESV_ID' => $RESV_ID];
        //$sql = "SELECT  b.* ,a.DOC_FILE_PATH FROM FLXY_CUSTOMER b LEFT JOIN FLXY_DOCUMENTS a ON a.DOC_CUST_ID = b.CUST_ID WHERE b.CUST_ID=:CUST_ID: OR a.DOC_FILE_TYPE ='PROOF'";
        $sql = "SELECT fc.*, fd.DOC_FILE_PATH, st.sname, ci.ctname
                FROM FLXY_CUSTOMER fc 
                left join FLXY_DOCUMENTS as fd on fc.CUST_ID = fd.DOC_CUST_ID AND fd.DOC_RESV_ID = :RESV_ID: AND fd.DOC_FILE_TYPE = 'PROOF'
                left join STATE as st on fc.CUST_STATE_ID = st.id
                left join CITY as ci on fc.CUST_CITY = ci.id
                WHERE fc.CUST_ID = :CUST_ID:";
        $data = $this->DB->query($sql, $param)->getRowArray();

        if (!empty($data)) {
            $data['DOCS'] = NULL;
            if ($data['DOC_FILE_PATH']) {
                $files_array = explode(',', $data['DOC_FILE_PATH']);
                foreach ($files_array as $key => $value) {
                    $files[$value] = $filePath . '/' . $value;
                }
                $data['DOCS'] = $files;
            }

            $result = responseJson(200, true, ["msg" => "Fetch the user details"], $data);
        } else {
            $result = responseJson(500, true, ["msg" => "user details fetching failed"]);
        }

        return $this->respond($result);
    }

    /*  FUNCTION : DELETE THE UPLOADED DOC( proof)
        METHOD: DELETE 
        INPUT : Header Authorization- Token
        OUTPUT : DELETED STATUS.  */
    public function deleteUploadedDOC()
    {
        $user_id = $this->request->user['USR_ID'];
        $return = false;

        $CUST_ID = $this->request->getVar("customerId"); //  proof
        $filename = $this->request->getVar("filename"); // or path
        $RESID = $this->request->getVar("reservationId");

        // fetch details from db
        $doc_data = $this->DB->table('FLXY_DOCUMENTS')->select('*')->where(['DOC_CUST_ID' => $CUST_ID,  'DOC_FILE_TYPE' => 'PROOF', 'DOC_RESV_ID' => $RESID])->get()->getRowArray();
        // echo $this->DB->getLastQuery()->getQuery();die;
        if (empty($doc_data)) {
            return $this->respond(responseJson(500, true, ["msg" => "No Documents found for the customer = " . $CUST_ID . " with reservation =" . $RESID]));
            die;
        }
        $filenames = $doc_data['DOC_FILE_PATH'];

        $filename_array = explode(',', $filenames);

        if (count($filename_array) == 1) {
            $this->DB->table('FLXY_DOCUMENTS')->where(['DOC_CUST_ID' => $CUST_ID, 'DOC_FILE_TYPE' => 'PROOF', 'DOC_RESV_ID' => $RESID])->delete();
            return $this->respond(responseJson(200, false, ['msg' => "Documents deletes successfully."]));
        }

        // inarray then delete else msg 
        $pos = array_search($filename, $filename_array);
        if ($pos >= 0) {
            $flag = true;
        } else {
            $flag = false;
        }

        if ($flag) {
            unset($filename_array[$pos]);

            $folderPath = $_SERVER['DOCUMENT_ROOT'] . "/assets/Uploads/userDocuments/proof/" .  $filename;
            // echo $folderPath;die;
            //var_dump(file_exists($folderPath));die;
            if (file_exists($folderPath)) {
                unlink($folderPath);
                $data = [
                    "DOC_CUST_ID" => $CUST_ID,
                    "DOC_FILE_PATH" => implode(',', $filename_array),
                    "DOC_UPDATE_UID" => $user_id,
                    "DOC_UPDATE_DT" => date("Y-m-d H:i:s")
                ];
                $return = $this->DB->table('FLXY_DOCUMENTS')->where(['DOC_CUST_ID' => $CUST_ID, 'DOC_FILE_TYPE' => 'PROOF', 'DOC_RESV_ID' => $RESID])->update($data);
            }


            if ($return)
                $result = responseJson(200, false, ["msg" => "Documents deleted successfully"], $return);
            else
                $result = responseJson(500, true, ["msg" => "File not found on server"]);

            return $this->respond($result);
        }

        return $this->respond(responseJson(500, true, ["msg" => "Something went worng"]));
    }

    public function deleteVaccine()
    {
        $reservation_id = $this->request->getVar('reservationId');
        $customer_id = $this->request->getVar('customerId');

        $file_name = $this->request->getVar('filename');

        $vaccine_detail = $this->VaccineDetail->where('VACC_CUST_ID', $customer_id)->where('VACC_RESV_ID', $reservation_id)->first();
        $docs = explode(",", $vaccine_detail['VACC_FILE_PATH']);
        foreach ($docs as $index => $doc) {
            if ($doc == $file_name) {
                unset($docs[$index]);

                $folder_path = $_SERVER['DOCUMENT_ROOT'] . "/assets/Uploads/userDocuments/proof/" .  $file_name;
                if (file_exists($folder_path))
                    unlink($folder_path);
            }
        }

        $file_names = implode(",", $docs);
        $response = $this->VaccineDetail
            ->where('VACC_CUST_ID', $customer_id)
            ->where('VACC_RESV_ID', $reservation_id)
            ->set(['VACC_FILE_PATH' => $file_names])
            ->update();

        if (!$response)
            return $this->respond(responseJson(500, true, ['msg' => 'delete failed']));

        return $this->respond(responseJson(200, false, ['msg' => 'Doc deleted']));
    }

    public function fetchVaccineDetails()
    {
        $reservation_id = $this->request->getVar('reservationId');
        $customer_id = $this->request->getVar('customerId');

        $vaccine_detail = $this->VaccineDetail->where('VACC_CUST_ID', $customer_id)->where('VACC_RESV_ID', $reservation_id)->first();
        $vaccine_detail['vaccines'] = $this->DB->query("select VT_ID as id, VT_NAME as label from FLXY_VACCINE_TYPES")->getResultArray();

        $docs = [];
        if (isset($vaccine_detail['VACC_FILE_PATH']) && !empty($vaccine_detail['VACC_FILE_PATH'])) {
            $docs = explode(",", $vaccine_detail['VACC_FILE_PATH']);
            foreach ($docs as $index => $doc) {
                $doc_name = getOriginalFileName($doc);
                $doc_url = base_url("assets/Uploads/userDocuments/vaccination/$doc");

                $doc_array = explode(".", $doc);
                $doc_type = getFileType(end($doc_array));

                $docs[$index] = ['name' => $doc_name, 'url' => $doc_url, 'type' => $doc_type];
            }
        }

        $vaccine_detail['docs'] = $docs;

        return $this->respond(responseJson(200, false, ['msg' => 'Vaccine Details'], $vaccine_detail));
    }

    public function vaccineUpload()
    {
        $user_id = $this->request->user['USR_ID'];
        $reservation_id = $this->request->getVar('reservationId');
        $customer_id = $this->request->getVar('customerId');

        $validate = $this->validate([
            'files' => [
                'uploaded[files]',
                'mime_in[files,image/png,image/jpeg,image/jpg,application/pdf]',
                'max_size[files,50000]',
            ],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $fileArry = $this->request->getFileMultiple('files');

        foreach ($fileArry as $key => $file) {
            if (!$file->isValid()) {
                return $this->respond(responseJson(500, true, ['msg' => "Please upload valid file. This file '{$file->getClientName()}' is not valid"]));
            }
        }

        // Code for file upload [vaccine is uploading to FLXY_VACCINE_DETAILS table]
        $file_names = '';
        if (!empty($fileArry)) {
            foreach ($fileArry as $key => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'assets/Uploads/userDocuments/vaccination', $newName);

                    $comma = '';
                    if (isset($fileArry[$key + 1])) {
                        $comma = ',';
                    }

                    $file_names .= $newName . $comma;
                }
            }
        }

        $vaccine_detail = $this->VaccineDetail->where('VACC_CUST_ID', $customer_id)->where('VACC_RESV_ID', $reservation_id)->first();
        if (empty($vaccine_detail)) {
            $data = [
                "VACC_CUST_ID" => $customer_id,
                "VACC_DETAILS" => '', // values will be -- vaccinated, medicallyExempt, vaccinationLater 
                "VACC_LAST_DT" => '',
                "VACC_TYPE" => '',
                "VACC_ISSUED_COUNTRY" => '',
                "VACC_IS_VERIFY" => 0,
                "VACC_FILE_PATH" => $file_names,
                "VACC_CREATE_UID" => $user_id,
                "VACC_UPDATE_UID" => $user_id,
                "VACC_RESV_ID" => $reservation_id
            ];
            $response = $this->VaccineDetail->insert($data);
        } else {
            if (!empty($vaccine_detail['VACC_FILE_PATH'])) {
                $file_names = $vaccine_detail['VACC_FILE_PATH'] . "," . $file_names;
            }

            $data = [
                "VACC_FILE_PATH" => $file_names,
                "VACC_UPDATE_UID" => $user_id,
            ];

            $response = $this->VaccineDetail->where('VACC_CUST_ID', $customer_id)->where('VACC_RESV_ID', $reservation_id)->set($data)->update();
        }

        if (!$response)
            return $this->respond(responseJson(500, true, ['msg' => 'Insert/Update failed']));

        return $this->respond(responseJson(200, false, ['msg' => 'Doc uploaded']));
    }

    /* FUNCTION : Vaccination Form
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : Update the Vaccination details in table.  */

    public function vaccineForm()
    {
        $user_id = $this->request->user['USR_ID'];
        $customer_id = $this->request->getVar('VACC_CUST_ID');
        $reservation_id = $this->request->getVar('VACC_RESV_ID');

        $validate = $this->validate([
            'VACC_CUST_ID' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Error: Invalid Guest.'
                ]
            ],
            'VACC_RESV_ID' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Error: Invalid Reservation.'
                ]
            ],
            'VACC_DETAILS' => 'required',
            'VACC_LAST_DT' => 'required',
            'VACC_TYPE' => 'required',
            'VACC_ISSUED_COUNTRY' => 'required',
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $data = [
            "VACC_CUST_ID" => $customer_id,
            "VACC_DETAILS" => $this->request->getVar("VACC_DETAILS"), // values will be -- vaccinated, medicallyExempt, vaccinationLater 
            "VACC_LAST_DT" => $this->request->getVar("VACC_LAST_DT"),
            "VACC_TYPE" => $this->request->getVar("VACC_TYPE"),
            "VACC_ISSUED_COUNTRY" => $this->request->getVar("VACC_ISSUED_COUNTRY"),
            "VACC_IS_VERIFY" => 0,
            "VACC_FILE_PATH" => '',
            "VACC_RESV_ID" => $reservation_id,
            "VACC_CREATE_UID" => $user_id,
            "VACC_UPDATE_UID" => $user_id,
        ];

        $vaccine_detail = $this->VaccineDetail->where('VACC_CUST_ID', $customer_id)->where('VACC_RESV_ID', $reservation_id)->first();
        if (empty($vaccine_detail)) {
            $response = $this->VaccineDetail->insert($data);
        } else {
            unset($data['VACC_FILE_PATH']);
            unset($data['VACC_CREATE_UID']);

            $response = $this->VaccineDetail->where('VACC_CUST_ID', $customer_id)->where('VACC_RESV_ID', $reservation_id)->set($data)->update();
        }

        if ($response)
            $result = responseJson(200, false, ["msg" => "Added the guest vaccine details"]);
        else
            $result = responseJson(500, true, ["msg" => "Insert/Update  Failed"]);

        return $this->respond($result);
    }

    /*  FUNCTION : CHECKIN COMPLETE (Accepting terms and uploading the signature)
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : update the signature.
*/
    public function acceptAndSignatureUpload()
    {
        $user = $this->request->user;
        $USR_ID = $user['USR_ID'];

        // for admin => will get customerId from parameters
        $cusUserID = $this->request->getVar('customerId') ?? $user['USR_CUST_ID'];
        $resID = $this->request->getVar('reservationId');

        $validate = $this->validate([
            'estimatedTimeOfArrival' => 'required',
            'signature' =>  [
                'uploaded[signature]',

                'max_size[signature,50000]',
            ],
        ]);

        $reservation_status = 'Pre Checked-In';
        if ($user['USR_ROLE_ID'] == '1')
            $reservation_status = 'Checked-In';

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $dataRes = [
            "RESV_ETA" => $this->request->getVar("estimatedTimeOfArrival"),
            "RESV_UPDATE_UID" => $USR_ID,
            "RESV_UPDATE_DT" => date("d-M-Y"),
            "RESV_STATUS" => $reservation_status,
        ];

        // update the signature in the documents table
        $doc_file = $this->request->getFile('signature');
        $doc_name = $doc_file->getName();
        $folderPath = "assets/Uploads/userDocuments/signature/";
        $doc_up = documentUpload($doc_file, $doc_name, $cusUserID, $folderPath);

        $data = [
            "DOC_CUST_ID" => $cusUserID,
            "DOC_IS_VERIFY" => 0,
            "DOC_FILE_PATH" => $doc_up['RESPONSE']['OUTPUT'],
            "DOC_FILE_TYPE" => 'SIGN',
            "DOC_RESV_ID" => $resID,
            "DOC_FILE_DESC" => "",
            "DOC_UPDATE_UID" => $USR_ID,
            "DOC_UPDATE_DT" => date("d-M-Y")
        ];

        if ($doc_up['SUCCESS'] == 200) {
            // check wheather there is any entry with this user. 
            $doc_data = $this->DB->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_FILE_PATH,DOC_CUST_ID,DOC_FILE_TYPE')->where(['DOC_CUST_ID' => $cusUserID, 'DOC_FILE_TYPE' => 'SIGN', 'DOC_RESV_ID' => $resID])->get()->getRowArray();

            if (!empty($doc_data)) {

                $update_data = $this->DB->table('FLXY_DOCUMENTS')->where('DOC_ID', $doc_data['DOC_ID'])->update($data);
            } else {

                $update_data = $this->DB->table('FLXY_DOCUMENTS')->insert($data);
            }

            $res_data = $this->DB->table('FLXY_RESERVATION')->where('RESV_ID', $resID)->update($dataRes);

            if ($update_data &&  $res_data) {
                $this->ReservationAssetRepository->attachAssetList($USR_ID, $resID);

                $result = responseJson(200, false, ["msg" => "File uploaded successfully"], ["path" => base_url($folderPath . $doc_up['RESPONSE']['OUTPUT'])]);
            } else
                $result = responseJson(500, true, ["msg" => "Failed to upload image or updation in reservation"]);


            return $this->respond($result);
        } else {
            return $this->respond(responseJson(500, true, ["msg" => "Upload Failed Please try again"]));
        }

        return $this->respond(responseJson(500, true, ["msg" => "Something went wrong"]));
    }

    // ----------------------------------------------------------------------- MAINTENANCE REQUEST API -------------------------------------------//

    /*  FUNCTION : CREATE MAINTENANCE REQUEST
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT :STATUS OF CREATION  */
    public function createRequest()
    {
        $user = $this->request->user;
        $CUST_ID = $user['USR_CUST_ID'];

        $validate = $this->validate([
            'type' => 'required',
            'category' => 'required',
            'roomNo' => 'required',
            'reservationId' => 'required'

        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $fileNames = '';
        $fileArry = $this->request->getFileMultiple('attachment');
        if (!empty($fileArry)) {
            foreach ($fileArry as $key => $file) {
                if (!$file->isValid()) {
                    return $this->respond(responseJson(500, true, ['msg' => "Please upload valid file. This file '{$file->getClientName()}' is not valid"]));
                }
            }
        }
        if (!empty($fileArry)) {
            foreach ($fileArry as $key => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'assets/Uploads/Maintenance', $newName);
                    $comma = '';

                    if (isset($fileArry[$key + 1]) && $fileArry[$key + 1]->isValid()) {
                        $comma = ',';
                    }

                    if ($newName)
                        $fileNames .= $newName . $comma;
                }
            }
        }


        $data = [
            "CUST_NAME" => $CUST_ID,
            "MAINT_TYPE" => $this->request->getVar("type"),
            "MAINT_CATEGORY" => $this->request->getVar("category"),
            "MAINT_SUB_CATEGORY" => $this->request->getVar("subCategory"),
            "MAINT_DETAILS" => $this->request->getVar("details"),
            "MAINT_PREFERRED_DT" => date("d-M-Y", strtotime($this->request->getVar("preferredDate"))),
            "MAINT_PREFERRED_TIME" => date("d-M-Y H:i:s", strtotime($this->request->getVar("preferredTime"))),
            "MAINT_ATTACHMENT" => $fileNames,
            "MAINT_STATUS" => "New",
            "MAINT_ROOM_NO" => $this->request->getVar("roomNo"),
            "MAINT_RESV_ID" => $this->request->getVar("reservationId"),
            "MAINT_CREATE_DT" => date("Y-m-d H:i:s"),
            "MAINT_UPDATE_DT" => date("Y-m-d H:i:s"),
            "MAINT_CREATE_UID" => $CUST_ID,
            "MAINT_UPDATE_UID" => $CUST_ID
        ];

        $ins = $this->DB->table('FLXY_MAINTENANCE')->insert($data);
        if ($ins)
            $result = responseJson(200, false, ["msg" => "Maintenance request created"]);
        else
            $result = responseJson(500, true, ["msg" => "Creation Failed"]);

        return $this->respond($result);
    }

    /*  FUNCTION : LIST MAINTENANCE REQUEST
    METHOD: GET 
    INPUT : Header Authorization- Token
    OUTPUT : LIST OF ALL MAINTENANCE REQUEST ADDED.
*/

    public function listRequests($reqID = null)
    {

        $user = $this->request->user;
        $cust_id = $user['USR_CUST_ID'];
        $data = [];

        if ($reqID) {
            $param = ['MAINT_ID' => $reqID];
            $sql = "SELECT a.*, CONCAT_WS(' ', b.CUST_FIRST_NAME, b.CUST_LAST_NAME) as NAME,c.MAINT_CATEGORY_TYPE,c.MAINT_CATEGORY as MAINT_CATEGORY_TEXT,d.MAINT_SUBCATEGORY FROM FLXY_MAINTENANCE a 
                        LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.CUST_NAME
                        LEFT JOIN FLXY_MAINTENANCE_CATEGORY c ON c.MAINT_CAT_ID = a.MAINT_CATEGORY
			            LEFT JOIN FLXY_MAINTENANCE_SUBCATEGORY d ON d.MAINT_SUBCAT_ID = a.MAINT_SUB_CATEGORY
                        WHERE MAINT_ID=:MAINT_ID: order by a.MAINT_ID desc";
            $data = $this->DB->query($sql, $param)->getRowArray();
        } else {
            $param = ['CUST_NAME' => $cust_id];
            $sql = "SELECT a.*,c.MAINT_CATEGORY_TYPE,c.MAINT_CATEGORY as MAINT_CATEGORY_TEXT,d.MAINT_SUBCATEGORY FROM FLXY_MAINTENANCE a
		    LEFT JOIN FLXY_MAINTENANCE_CATEGORY c ON c.MAINT_CAT_ID = a.MAINT_CATEGORY
		    LEFT JOIN FLXY_MAINTENANCE_SUBCATEGORY d ON d.MAINT_SUBCAT_ID = a.MAINT_SUB_CATEGORY
                    WHERE CUST_NAME = :CUST_NAME: order by a.MAINT_ID desc";
            $data = $this->DB->query($sql, $param)->getResultArray();
        }

        foreach ($data as $i => $maintenance_request) {
            $attachments = [];
            if ($maintenance_request['MAINT_ATTACHMENT']) {
                $attachments = explode(",", $maintenance_request['MAINT_ATTACHMENT']);

                foreach ($attachments as $j => $attachment) {
                    $name = $attachment;
                    $url = base_url("assets/Uploads/Maintenance/$attachment");

                    $attachment_array = explode(".", $attachment);
                    $type = end($attachment_array);

                    $attachments[$j] = ['name' => $name, 'url' => $url, 'type' => $type];
                }
            }

            $data[$i]['MAINT_ATTACHMENT'] = $attachments;
        }

        if (!empty($data))
            $result = responseJson(200, false, ["msg" => "Maintenance list fetched Successfully"], $data);
        else
            $result = responseJson(200, false, ["msg" => "No Request List for this user"], []);

        return $this->respond($result);
    }
    /*  FUNCTION : GET  MAINTENANCE REQUEST GET CATEGORY LIST
    METHOD: GET
    INPUT : Header Authorization- Token
    OUTPUT : LIST OF CATEGORIES   */
    public function maintenanceCategoryList()
    {
        $category_type = $this->request->getVar('category_type');
        $sql = "SELECT MAINT_CAT_ID,MAINT_CATEGORY FROM FLXY_MAINTENANCE_CATEGORY where MAINT_CATEGORY_TYPE = :category_type:";
        $params = ['category_type' => $category_type];

        $response = $this->DB->query($sql, $params)->getResultArray();
        if ($response) {
            $result = responseJson(200, false, ["msg" => "Maintenance list categories fetched Successfully"], $response);
        } else {
            $result = responseJson(500, True, ["msg" => "Server Error"]);
        }
        return $this->respond($result);
    }
    /*  FUNCTION : GET  MAINTENANCE REQUEST  SUBCATEGORY LIST
    METHOD: GET
    INPUT : Header Authorization- Token , CategoryID
    OUTPUT : LIST OF SUBCATEGORIES   */

    public function maintenanceSubCatByCategoryID()
    {
        $param = ['MAINT_CAT_ID' => $this->request->getVar("category")];
        $sql = "SELECT a.MAINT_CAT_ID,b.MAINT_SUBCATEGORY ,b.MAINT_SUBCAT_ID FROM FLXY_MAINTENANCE_CATEGORY a
        inner JOIN FLXY_MAINTENANCE_SUBCATEGORY b ON b.MAINT_CAT_ID = a.MAINT_CAT_ID WHERE a.MAINT_CAT_ID =:MAINT_CAT_ID:";
        $response = $this->DB->query($sql, $param)->getResultArray();

        $result = responseJson(200, false, ["msg" => "Maintenance list sub categories fetched Successfully"], $response);
        return $this->respond($result);
    }

    public function apartmentList()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];

        if ($this->request->user['USR_ROLE_ID'] == '1') {
            $room_list = $this->DB->table('FLXY_ROOM')
                ->select("RM_NO as RESV_ROOM, RM_ID, RESV_ID, (CASE WHEN RESV_ID is not null THEN concat(RESV_ID, '-', CUST_FIRST_NAME, ' ', CUST_LAST_NAME) ELSE NULL END) as ID_NAME")
                ->join('FLXY_RESERVATION', "RM_NO = RESV_ROOM", 'left')
                ->join('FLXY_CUSTOMER', 'RESV_NAME = CUST_ID', 'left')
                ->whereIn('RESV_STATUS', ['Checked-In', 'Checked-Out-Requested'])
                ->get()
                ->getResult();

            // $room_list = [];

            // $rooms = $this->Room->select('RM_ID, RM_NO')->findAll();            
            // foreach ($rooms as $index => $room) {
            //     $reservations = $this->Reservation
            //         ->select("RESV_ID, (CASE WHEN RESV_ID is not null THEN concat(RESV_ID, '-', CUST_FIRST_NAME, ' ', CUST_LAST_NAME) ELSE NULL END) as ID_NAME")
            //         ->join('FLXY_CUSTOMER', 'RESV_NAME = CUST_ID', 'left')
            //         ->where('RESV_ROOM', $room['RM_NO'])
            //         ->whereIn('RESV_STATUS', ['Checked-In', 'Checked-Out-Requested'])
            //         ->findAll();

            //     if (empty($reservations))
            //         unset($rooms[$index]);
            //     else {
            //         $rooms[$index]['reservations'] = $reservations;
            //         $room_list[] = $rooms[$index];
            //     }
            // }
        } else {
            $room_list = $this->DB->table('FLXY_RESERVATION')
                ->select("RESV_ID, RESV_ROOM, RM_ID, concat(RESV_ID, '-', CUST_FIRST_NAME, ' ', CUST_LAST_NAME) as ID_NAME")
                ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
                ->join('FLXY_CUSTOMER', 'RESV_NAME = CUST_ID', 'left')
                ->where('RESV_NAME', $customer_id)
                ->where('RESV_STATUS', 'Checked-In')
                ->where('RESV_ROOM !=', '')
                ->get()
                ->getResult();
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Room list'], $room_list));
    }

    /*  FUNCTION : FEEDBACK ADDING FROM GUEST
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : RESPONSE OD ADDITION   */

    public function addFeedBack()
    {
        $validate = $this->validate([
            'rating' => 'required|min_length[1]|less_than_equal_to[5]|not_in_list[0]',
        ]);

        if (!$validate) {

            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $cust_id = $this->request->user['USR_CUST_ID'];
        $data = [
            "FB_RATINGS" => $this->request->getVar("rating"),
            "FB_CUST_ID"  => $cust_id,
            "FB_DESCRIPTION" => $this->request->getVar("comments"),
            "FB_MODEL" => $this->request->getVar("model") ?? null,
            "FB_MODEL_ID" => $this->request->getVar("model_id") ?? null,
            "FB_CREATE_DT" => date("d-M-Y H:i"),
            "FB_CREATE_UID" => $cust_id,
            "FB_UPDATE_DT" => date("d-M-Y"),
            "FB_UPDATE_UID" => $cust_id
        ];

        $ins = $this->DB->table('FLXY_FEEDBACK')->insert($data);
        if ($ins)
            $result = responseJson(200, false, ["msg" => "Feedback Added"]);
        else
            $result = responseJson(500, true, ["msg" => "Feedback addition Failed"]);

        return $this->respond($result);
    }

    /*  FUNCTION : BUS SHUTTLE FETCHING 
    METHOD: GET 
    INPUT : Header Authorization- Token
    OUTPUT : LIST OF SHUTTLES         */
    public function listShuttles($shutleID = null)
    {
        if ($shutleID) {
            $param = ['SHUTL_ID' => $shutleID];
            $sql = "SELECT FLXY_SHUTTLE_ROUTE.*, SHUTL_START_AT, SHUTL_STAGE_NAME, SHUTL_STAGE_IMAGE FROM FLXY_SHUTTLE_ROUTE 
                        left join FLXY_SHUTTLE on FSR_SHUTTLE_ID = SHUTL_ID
                        left join FLXY_SHUTL_STAGES on FSR_STAGE_ID = SHUTL_STAGE_ID
                        WHERE FSR_SHUTTLE_ID = :SHUTL_ID: order by FSR_ORDER_NO";

            $data = $this->DB->query($sql, $param)->getResultArray();

            foreach ($data as $index => $stage) {
                $shuttle_start_at = $stage['SHUTL_START_AT'];
                $duration_mins = $stage['FSR_DURATION_MINS'];

                $data[$index]['FSR_START_TIME'] = date('Y-m-d H:i:s', strtotime($shuttle_start_at) + ($duration_mins * 60));
            }
        } else {
            $sql = "SELECT fs.*, 
                        (select SHUTL_STAGE_NAME from FLXY_SHUTL_STAGES where SHUTL_STAGE_ID = fs.SHUTL_FROM) as FROM_STAGE,
                        (select SHUTL_STAGE_NAME from FLXY_SHUTL_STAGES where SHUTL_STAGE_ID = fs.SHUTL_TO) as TO_STAGE 
                        FROM FLXY_SHUTTLE as fs";
            $data = $this->DB->query($sql)->getResultArray();
        }

        if ($data) {

            $result = responseJson(200, false, ["msg" => "Shuttles deatils fetched Successfully"], $data);
        } else {
            $result = responseJson(500, true, ["msg" => "Shuttles deatils fetched Failed"]);
        }
        return $this->respond($result);
    }

    //------------------------------------------------------------------------------------- HANDBOOK ----------------------------------------------------------------------------------------------//
    /*  FUNCTION : TO GET HANDBOOK URL 
    METHOD: GET 
    INPUT : Header Authorization- Token
    OUTPUT : HANDBOOK URL         */
    public function getHandBookURL()
    {
        $book = $this->PropertyInfo->first();
        // $path = 'assets/Uploads/handbook/hotel-handbook.pdf';

        if (file_exists($book['PI_URL']))
            $result = responseJson(200, false, ["msg" => "Handbook URL fetched"], ['url' => base_url($book['PI_URL'])]);
        else
            $result = responseJson(500, false, ["msg" => "No Handbook file uploaded"]);

        return $this->respond($result);
    }

    // ------------------------------------------------------------------------------------ ADMIN APP APIS ---------------------------------------------------------------------------------------//




    // ----------- END API FOR FLEXIGUEST ----------------//

    public function lookupApi()
    {
        $data['salutations'] = $this->DB->query("select SA_ID as id, SA_NAME as label from FLXY_SALUTATIONS")->getResultArray();
        $data['genders'] = $this->DB->query("select GE_ID as id, GE_NAME as label from FLXY_GENDERS")->getResultArray();
        $data['doc_types'] = $this->DB->query("select DT_ID as id, DT_NAME as label from FLXY_DOC_TYPES")->getResultArray();

        $result = responseJson(200, false, ['msg' => 'LookUp Api'], $data);
        return $this->respond($result);
    }

    public function verifyDocuments()
    {
        $customer_id = $this->request->getVar('customer_id');
        $reservation_id = $this->request->getVar('reservation_id');

        $result = $this->ReservationRepository->verifyDocuments($customer_id, $reservation_id);
        return $this->respond($result);
    }

    public function guestCheckedIn()
    {
        $user = $this->request->user;
        $user_id = $user['USR_ID'];

        $reservation_id = $this->request->getVar('reservation_id');

        $params = ['reservation_id' => $reservation_id];
        $check_exist = $this->DB->query('select * from FLXY_RESERVATION where RESV_ID = :reservation_id:', $params)->getResultArray();
        if (!count($check_exist))
            return $this->respond(responseJson(404, true, ['msg' => 'No reservation found.']));

        $this->DB->query("update FLXY_RESERVATION set RESV_STATUS = 'Checked-In' where RESV_ID = :reservation_id:", $params);
        $this->ReservationAssetRepository->attachAssetList($user_id, $reservation_id);

        return $this->respond(responseJson(200, false, ['msg' => 'Guest checked-in successfully.']));
    }

    public function getState()
    {
        $country_code = $this->request->getVar('country_code');
        $states = $this->State->where('country_code', $country_code)->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'State List'], $states));
    }

    public function getCity()
    {
        $state_id = $this->request->getVar('state_id');
        $cities = $this->City->where('state_id', $state_id)->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'Cities List'], $cities));
    }

    public function health()
    {
        echo "OK";
        die();
    }
}
