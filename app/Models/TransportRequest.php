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
        'TR_GUEST_IMAGE',
        'TR_TRAVEL_TYPE',
        'TR_TRANSPORT_TYPE_ID',
        'TR_TRAVEL_PURPOSE',
        'TR_ADULTS',
        'TR_CHILDREN',
        'TR_IS_CHILD_SEAT_REQUIRED',
        'TR_TOTAL_PASSENGERS',
        'TR_PICKUP_DATE',
        'TR_PICKUP_TIME',
        'TR_PICKUP_POINT_ID',
        'TR_PICKUP_PLACE',
        'TR_PICKUP_INSTRUCTIONS',
        'TR_PICKUP_VEHICLE_Detail',
        'TR_PICKUP_DRIVER_DETAIL',
        'TR_DROPOFF_DATE',
        'TR_DROPOFF_TIME',
        'TR_DROPOFF_POINT_ID',
        'TR_DROPOFF_PLACE',
        'TR_DROPOFF_INSTRUCTIONS',
        'TR_DROPOFF_VEHICLE_Detail',
        'TR_DROPOFF_DRIVER_DETAIL',
        'TR_FLIGHT_CARRIER_ID',
        'TR_FLIGHT_DATE',
        'TR_FLIGHT_TIME',
        'TR_REMARKS',
        'TR_REMINDER_REQUIRED',
        'TR_STATUS',
        'TR_TOTAL_AMOUNT',
        'TR_PAYMENT_METHOD', // Pay at Reception, Samsung Pay, Credit/Debit card
        'TR_PAYMENT_STATUS', // UnPaid, Paid
        'TR_CREATED_BY',
        'TR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'TR_CREATED_AT';
    protected $updatedField  = 'TR_UPDATED_AT';
}
