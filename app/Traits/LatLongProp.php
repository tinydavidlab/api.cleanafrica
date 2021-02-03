<?php


namespace App\Traits;


trait LatLongProp
{
    public function getLinkAttribute(): string
    {
        $latitude  = $this->getAttribute( 'latitude' );
        $longitude = $this->getAttribute( 'longitude' );

        return "http://maps.google.com/maps?q={$latitude},{$longitude}";
    }
}
