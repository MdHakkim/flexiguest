<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Reservation;
use CodeIgniter\API\ResponseTrait;

class ReservationRepository extends BaseController
{
    use ResponseTrait;

    private $DB;
    private $Reservation;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->Reservation = new Reservation();
    }

    public function reservationById($reservation_id)
    {
        return $this->Reservation
            ->select("FLXY_RESERVATION.*, fc.*, fr.*,
                        concat(fc.CUST_FIRST_NAME, ' ', fc.CUST_LAST_NAME) as CUST_NAME,
                        co.cname as COUNTRY_NAME,
                        st.sname as STATE_NAME,
                        ci.ctname as CITY_NAME")
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
            ->join('COUNTRY as co', 'fc.CUST_COUNTRY = co.iso2', 'left')
            ->join('STATE as st', 'fc.CUST_STATE = st.state_code', 'left')
            ->join('CITY as ci', 'fc.CUST_CITY = ci.id', 'left')
            ->join('FLXY_ROOM as fr', 'FLXY_RESERVATION.RESV_ROOM = fr.RM_NO', 'left')
            ->where('RESV_ID', $reservation_id)
            ->first();
    }

    public function allReservations($where_condition = '1 = 1')
    {
        // $where_condition = "RESV_STATUS = 'Due Pre Check-In' or RESV_STATUS = 'Pre Checked-In' or RESV_STATUS = 'Checked-In'";

        return $this->Reservation
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
            ->join('FLXY_ROOM as rm', 'FLXY_RESERVATION.RESV_ROOM = rm.RM_NO', 'left')
            ->where($where_condition)
            ->findAll();
    }

    public function reservationsOfCustomer($where_condition)
    {
        return $this->Reservation
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->where($where_condition)
            ->findAll();
    }

    public function currentReservations()
    {
        return $this->Reservation
            ->where('RESV_STATUS', 'Due Pre Check-In')
            ->orWhere('RESV_STATUS', 'Pre Checked-In')
            ->orWhere('RESV_STATUS', 'Checked-In')
            ->orderBy('RESV_ID', 'desc')
            ->findAll();
    }

    public function updateReservation($data, $where_condition)
    {
        return $this->Reservation->where($where_condition)->set($data)->update();
    }

    public function verifyDocuments($customer_id, $reservation_id)
    {
        $params = [
            'customer_id' => $customer_id,
            'reservation_id' => $reservation_id
        ];

        $sql = "select * from FLXY_DOCUMENTS where DOC_CUST_ID = :customer_id: and DOC_RESV_ID = :reservation_id:";
        $response = $this->DB->query($sql, $params)->getResultArray();
        if (empty($response))
            return responseJson(403, true, ['msg' => 'No documents uploaded yet.']);

        // $sql = "select * from FLXY_VACCINE_DETAILS where VACC_CUST_ID = :customer_id: and VACC_RESV_ID = :reservation_id:";
        // $response = $this->DB->query($sql, $params)->getResultArray();
        // if(empty($response))
        //     return responseJson(403, true, ['msg' => 'Vaccination details are not uploaded yet.']);

        $sql = "update FLXY_DOCUMENTS set DOC_IS_VERIFY = 1 where DOC_CUST_ID = :customer_id: and DOC_RESV_ID = :reservation_id:";
        $this->DB->query($sql, $params);

        // $sql = "update FLXY_VACCINE_DETAILS set VACC_IS_VERIFY = 1 where VACC_CUST_ID = :customer_id: and VACC_RESV_ID = :reservation_id:";
        // $this->DB->query($sql, $params);

        return responseJson(200, false, ['msg' => 'Documents are verified.']);
    }

    public function totalGuests()
    {
        return $this->Reservation->select('sum(RESV_ADULTS) as total_adults, sum(RESV_CHILDREN) as total_children, (sum(RESV_ADULTS) + sum(RESV_CHILDREN)) as total_guests')->first();
    }

    public function generateRegistrationCard($reservation_id)
    {
        $data = [];
        $sql = "SELECT RESV_ARRIVAL_DT, RESV_ROOM, RESV_DEPARTURE, RESV_NIGHT, RESV_ADULTS, RESV_CHILDREN, RESV_NO, 
                    CAST(RESV_RATE AS VARCHAR)RESV_RATE, RESV_RM_TYPE, RESV_NAME, RESV_STATUS,
                    CUST_FIRST_NAME, CUST_LAST_NAME, CUST_MOBILE, CUST_EMAIL, CUST_DOB, CUST_DOC_TYPE, CUST_DOC_NUMBER,
                    CONCAT(CUST_ADDRESS_1, CUST_ADDRESS_2, CUST_ADDRESS_3) AS CUST_ADDRESS, 
                    fd.DOC_FILE_PATH as SIGNATURE,
                    (SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ID=RESV_COMPANY) RESV_COMPANY_DESC, 
                    (SELECT ctname FROM CITY WHERE id=CUST_CITY) CUST_CITY_DESC, 
                    (SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC, 
                    (SELECT cname FROM COUNTRY WHERE ISO2=CUST_NATIONALITY) CUST_NATIONALITY_DESC
                    FROM FLXY_RESERVATION 
                        INNER JOIN FLXY_CUSTOMER ON FLXY_RESERVATION.RESV_NAME = FLXY_CUSTOMER.CUST_ID 
                        left join FLXY_DOCUMENTS as fd on FLXY_RESERVATION.RESV_ID = fd.DOC_RESV_ID and fd.DOC_FILE_TYPE = 'SIGN'
                        WHERE RESV_ID = $reservation_id";
        $data['response'] = $this->DB->query($sql)->getResultArray();
        $data['branding_logo'] = brandingLogo();

        $file_name = "assets/reservation-registration-cards/RES{$reservation_id}-RC.pdf";
        $view = "Reservation/RegisterCard";
        return generateInvoice($file_name, $view, $data);
    }
}
