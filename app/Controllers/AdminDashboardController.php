<?php

namespace App\Controllers;

use App\Controllers\Repositories\ConciergeRepository;
use App\Controllers\Repositories\LaundryAmenitiesRepository;
use App\Controllers\Repositories\MaintenanceRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\RestaurantRepository;
use App\Controllers\Repositories\RoomRepository;
use App\Controllers\Repositories\TransportRequestRepository;
use CodeIgniter\API\ResponseTrait;

class AdminDashboardController extends BaseController
{

    use ResponseTrait;

    private $ReservationRepository;
    private $RoomRepository;
    private $MaintenanceRepository;
    private $LaundryAmenitiesRepository;
    private $RestaurantRepository;
    private $ConciergeRepository;
    private $TransportRequestRepository;

    public function __construct()
    {
        $this->ReservationRepository = new ReservationRepository();
        $this->RoomRepository = new RoomRepository();
        $this->MaintenanceRepository = new MaintenanceRepository();
        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();
        $this->RestaurantRepository = new RestaurantRepository();
        $this->ConciergeRepository = new ConciergeRepository();
        $this->TransportRequestRepository = new TransportRequestRepository();
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
        $data = [
            'all_reservations' => 0,
            'walkin_reservations' => 0,
            'checkins_today' => 0,
            'checkouts_today' => 0,
            'arrivals_today' => 0,
            'cancelled_today' => 0,
            'in_house_rooms' => 0,
            'arrival_expected_rooms' => 0,
            'departure_expected_rooms' => 0,
            'complementary_arrivals' => 0,
            'complementary_departures' => 0,
            'complementary_checkedins' => 0,
            'room_revenue' => 0,
        ];

        $reservations = $this->ReservationRepository->allReservations();
        $data['all_reservations'] = count($reservations);

        foreach ($reservations as $reservation) {
            if ($reservation['RESV_SOURCE'] == 'WLK')
                $data['walkin_reservations']++;

            if ($reservation['RESV_ARRIVAL_DT'] == $today && in_array($reservation['RESV_STATUS'], ['Checked-In']))
                $data['checkins_today']++;

            if ($reservation['RESV_DEPARTURE'] == $today && in_array($reservation['RESV_STATUS'], ['Checked-Out']))
                $data['checkouts_today']++;

            if ($reservation['RESV_ARRIVAL_DT'] == $today && in_array($reservation['RESV_STATUS'], ['Due Pre Check-In', 'Pre Checked-In']))
                $data['arrivals_today']++;

            if (in_array($reservation['RESV_STATUS'], ['Cancelled']))
                $data['cancelled_today']++;

            if (in_array($reservation['RESV_STATUS'], ['Checked-In', 'Check-Out-Requested']))
                $data['in_house_rooms']++;

            if ($reservation['RESV_ARRIVAL_DT'] == $today && in_array($reservation['RESV_STATUS'], ['Due Pre Check-In', 'Pre Checked-In']) && !empty($reservation['RESV_ROOM']))
                $data['arrival_expected_rooms']++;

            if ($reservation['RESV_DEPARTURE'] == $today && in_array($reservation['RESV_STATUS'], ['Checked-In', 'Check-Out-Requested']))
                $data['departure_expected_rooms']++;

            if ($reservation['RESV_RATE_CODE'] == 'COMP' && $reservation['RESV_ARRIVAL_DT'] == $today && in_array($reservation['RESV_STATUS'], ['Due Pre Check-In', 'Pre Checked-In']))
                $data['complementary_arrivals']++;

            if ($reservation['RESV_RATE_CODE'] == 'COMP' && $reservation['RESV_DEPARTURE'] == $today && in_array($reservation['RESV_STATUS'], ['Checked-In', 'Check-Out-Requested', 'Checked-Out']))
                $data['complementary_departures']++;

            if ($reservation['RESV_RATE_CODE'] == 'COMP' && in_array($reservation['RESV_STATUS'], ['Checked-In', 'Check-Out-Requested']))
                $data['complementary_checkedins']++;

            if(!empty($reservation['RESV_RATE']))
                $data['room_revenue'] += doubleval($reservation['RESV_RATE']);
        }

        $data['room_revenue'] = strval($data['room_revenue']);
        $data['departure_rooms'] = $data['checkouts_today'];

        $total_guests = $this->ReservationRepository->totalGuests();
        $data['total_guests'] = $total_guests['total_adults'] . '/' . $total_guests['total_children'];

        $data['last_seven_dates'] = $this->lastSevenDates();

        $highest_checkins_count = $lowest_checkins_count = 0;
        $data['highest_checkins'] = $data['lowest_checkins'] = null;
        foreach ($data['last_seven_dates'] as $index => $date) {
            $where_condition = "RESV_ARRIVAL_DT = '{$date['date']}' and RESV_STATUS in ('Checked-In', 'Check-Out-Requested', 'Checked-Out')";
            $checkins_count = count($this->ReservationRepository->allReservations($where_condition));
            $data['last_seven_dates'][$index]['checkins'] = $checkins_count;

            if ($highest_checkins_count < $checkins_count) {
                $highest_checkins_count = $checkins_count;
                $data['highest_checkins'] = ['count' => $checkins_count, 'day' => $date['day']];
            }

            if ($lowest_checkins_count > $checkins_count) {
                $lowest_checkins_count = $checkins_count;
                $data['lowest_checkins'] = ['count' => $checkins_count, 'day' => $date['day']];
            }
        }

        $rooms = $this->RoomRepository->roomsWithStatus();
        $data['total_rooms'] = count($rooms);
        $data['average_room_revenue'] = $data['room_revenue'] / $data['total_rooms'];

        $data['clean_rooms'] = $data['dirty_rooms'] = $data['inspected_rooms'] = $data['out_of_service_rooms'] = $data['out_of_order_rooms'] = 0;

        foreach ($rooms as $room) {
            if ($room['ROOM_STATUS_ID'] == 1)
                $data['clean_rooms'] += 1;

            else if (is_null($room['ROOM_STATUS_ID']) || $room['ROOM_STATUS_ID'] == 2)
                $data['dirty_rooms'] += 1;

            else if ($room['ROOM_STATUS_ID'] == 3)
                $data['inspected_rooms'] += 1;

            else if ($room['ROOM_STATUS_ID'] == 4)
                $data['out_of_service_rooms'] += 1;

            else if ($room['ROOM_STATUS_ID'] == 5)
                $data['out_of_order_rooms'] += 1;
        }

        $data['ready_for_sell'] = (($data['total_rooms'] - $data['out_of_order_rooms']) - $data['in_house_rooms']);

        $data['maintenance_requests'] = count($this->MaintenanceRepository->allMaintenanceRequest("MAINT_STATUS in ('New', 'Assigned', 'In Progress')"));

        $data['amenities_orders'] = count($this->LaundryAmenitiesRepository->getLAOrders());
        $data['amenities_revenue'] = $this->LaundryAmenitiesRepository->laundryAmenitiesRevenue();

        $data['concierge_revenue'] = strval($this->ConciergeRepository->conciergeRevenue());

        $data['transport_request_revenue'] = strval($this->TransportRequestRepository->transportRequestRevenue());

        $data['restaurant_orders'] = count($this->RestaurantRepository->orderList($user));
        $data['fnb_revenue'] = strval($this->RestaurantRepository->restaurantRevenue());
        $data['other_revenue'] = $data['amenities_revenue'] + $data['concierge_revenue'] + $data['transport_request_revenue'];

        return $this->respond(responseJson(200, false, ['msg' => 'Stats'], $data));
    }
}
