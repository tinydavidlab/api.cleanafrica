<?php


namespace App\Traits;


use Carbon\Carbon;

trait TripProps
{
    public function getLinkAttribute(): string
    {
        $latitude  = $this->getAttribute( 'customer_latitude' );
        $longitude = $this->getAttribute( 'customer_longitude' );

        return "http://maps.google.com/maps?q={$latitude},{$longitude}";
    }

    /*public function setCollectorDateAttribute($value)
    {
        $this->attributes['collector_date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }*/
}
