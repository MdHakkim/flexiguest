<?php

namespace App\Models;

use CodeIgniter\Model;

class TransportRequest extends Model
{
    protected $table = 'FLXY_TRANSPORT_REQUESTS';
    protected $primaryKey = 'TR_ID';
    protected $allowedFields = [
        'TR_CUSTOMER_ID',
        'TR_RESERVATION_ID',
        'TR_ROOM_ID',
        'TR_GUEST_NAME',
        'TR_TRAVEL_TYPE',
        'TR_TRANSPORT_TYPE_ID',
        'TR_TRAVEL_PURPOSE',
        'TR_TRAVEL_DATE',
        'TR_TRAVEL_TIME',
        'TR_ADULTS',
        'TR_CHILDREN',
        'TR_IS_CHILD_SEAT_REQUIRED',
        'TR_TOTAL_PASSENGERS',
        'TR_PICKUP_POINT_ID',
        'TR_PICKUP_PLACE',
        'TR_PICKUP_INSTRUCTIONS',
        'TR_DROPOFF_POINT_ID',
        'TR_DROPOFF_PLACE',
        'TR_DROPOFF_INSTRUCTIONS',
        'TR_VEHICLE_NO',
        'TR_RETURN_VEHICLE_NO',
        'TR_FLIGHT_CARRIER_ID',
        'TR_FLIGHT_DATE',
        'TR_FLIGHT_TIME',
        'TR_REMARKS',
        'TR_REMINDER_REQUIRED',
        'TR_STATUS',
        'TR_PAYMENT_METHOD',
        'TR_CREATED_BY',
        'TR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'TR_CREATED_AT';
    protected $updatedField  = 'TR_UPDATED_AT';
}
