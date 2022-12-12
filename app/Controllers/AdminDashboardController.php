<?php

namespace App\Controllers;

use App\Controllers\Repositories\LaundryAmenitiesRepository;
use App\Controllers\Repositories\MaintenanceRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\RestaurantRepository;
use App\Controllers\Repositories\RoomRepository;
use CodeIgniter\API\ResponseTrait;

class AdminDashboardController extends BaseController
{

    use ResponseTrait;

    private $ReservationRepository;
    private $RoomRepository;
    private $MaintenanceRepository;
    private $LaundryAmenitiesRepository;
    private $RestaurantRepository;

    public function __construct()
    {
        $this->ReservationRepository = new ReservationRepository();
        $this->RoomRepository = new RoomRepository();
        $this->MaintenanceRepository = new MaintenanceRepository();
        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();
        $this->RestaurantRepository = new RestaurantRepository();
    }

    public function lastSevenDates()
    {
        $dates = [];
        foreach (range(1, 7) as $day)
            $dates[] = [
                'date' => date('Y-m-d', strtotime("-$day day")),
                'day' => date('D', strtotime("-$day day"))
            ];

        return $dates;
    }

    public function getStats()
    {
        $user = $this->request->user;
        $today = date('Y-m-d');

        // Reservations
        $data['all_reservations'] = count($this->ReservationRepository->allReservations());

        $where_condition = "RESV_ARRIVAL_DT = '$today' and RESV_STATUS in ('Checked-In')";
        $data['checkins_today'] = count($this->ReservationRepository->allReservations($where_condition));

        $where_condition = "RESV_DEPARTURE = '$today' and RESV_STATUS in ('Checked-Out')";
        $data['checkouts_today'] = count($this->ReservationRepository->allReservations($where_condition));

        $where_condition = "RESV_ARRIVAL_DT = '$today' and RESV_STATUS in ('Due Pre Check-In', 'Pre Checked-In')";
        $data['arrivals_today'] = count($this->ReservationRepository->allReservations($where_condition));

        $where_condition = "RESV_STATUS in ('Cancelled')";
        $data['cancelled_today'] = count($this->ReservationRepository->allReservations($where_condition));

        $data['last_seven_dates'] = $this->lastSevenDates();

        $highest_checkins_count = $lowest_checkins_count = 0;
        $data['highest_checkins'] = $data['lowest_checkins'] = null;
        foreach ($data['last_seven_dates'] as $index => $date) {
            $where_condition = "RESV_ARRIVAL_DT = '{$date['date']}' and RESV_STATUS in ('Checked-In', 'Check-Out-Requested', 'Checked-Out')";
            $checkins_count = count($this->ReservationRepository->allReservations($where_condition));
            $data['last_seven_dates'][$index]['checkins'] = $checkins_count;
            
            if($highest_checkins_count < $checkins_count) {
                $highest_checkins_count = $checkins_count;
                $data['highest_checkins'] = ['count' => $checkins_count, 'day' => $date['day']];
            }

            if($lowest_checkins_count > $checkins_count) {
                $lowest_checkins_count = $checkins_count;
                $data['lowest_checkins'] = ['count' => $checkins_count, 'day' => $date['day']];
            }
        }

        $data['total_guests'] = $this->ReservationRepository->totalGuests();
        $data['all_rooms'] = count($this->RoomRepository->allRooms());

        $data['maintenance_requests'] = count($this->MaintenanceRepository->allMaintenanceRequest());
        $data['amenities_orders'] = count($this->LaundryAmenitiesRepository->getLAOrders());
        $data['restaurant_orders'] = count($this->RestaurantRepository->orderList($user));

        return $this->respond(responseJson(200, false, ['msg' => 'Stats'], $data));
    }
}
