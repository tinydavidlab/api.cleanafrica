<?php

namespace App\Models;

use App\CanFilter;
use App\Traits\TripProps;
use App\Traits\TripRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes,
        CanFilter,
        TripProps,
        TripRelations;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_primary_phone_number',
        'customer_secondary_phone_number',
        'customer_apartment_number',
        'customer_country',
        'customer_division',
        'customer_subdivision',
        'customer_snoocode',
        'customer_latitude',
        'customer_longitude',
        'customer_latitude_number',
        'customer_longitude_number',

        'collector_id',
        'collector_name',
        'collector_date',
        'collector_time',
        'collector_signature',
        'collector_country',
        'collector_division',
        'collector_subdivision',
        'collector_snoocode',

        'bin_image',
        'property_image',
        'company_id',
        'truck_id',
        'notes',
        'delivery_status',
        'bin_liner_quantity',

    ];

    /*protected $dates = [
        'collector_date'
    ];

    protected $casts = [
        'collector_date' => 'datetime:Y-m-d',
    ];*/
}
