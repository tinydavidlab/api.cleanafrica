<?php

namespace App\Transformers;

use App\Models\Trip;
use League\Fractal\TransformerAbstract;

class TripTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'company'
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param Trip $trip
     * @return array
     */
    public function transform( Trip $trip ): array
    {
        return [
            'id' => $trip->getAttribute( 'id' ),
            'customer_name' => $trip->getAttribute( 'customer_name' ),
            'customer_primary_phone_number' => $trip->getAttribute( 'customer_primary_phone_number' ),
            'customer_secondary_phone_number' => $trip->getAttribute( 'customer_secondary_phone_number' ),
            'customer_apartment_number' => $trip->getAttribute( 'customer_apartment_number' ),
            'customer_country' => $trip->getAttribute( 'customer_country' ),
            'customer_division' => $trip->getAttribute( 'customer_division' ),
            'customer_subdivision' => $trip->getAttribute( 'customer_subdivision' ),
            'customer_snoocode' => $trip->getAttribute( 'customer_snoocode' ),
            'collector_country' => $trip->getAttribute( 'collector_country' ),
            'collector_division' => $trip->getAttribute( 'collector_division' ),
            'collector_subdivision' => $trip->getAttribute( 'collector_subdivision' ),
            'collector_snoocode' => $trip->getAttribute( 'collector_snoocode' ),
            'photo_1' => $trip->getAttribute( 'photo_1' ),
            'photo_2' => $trip->getAttribute( 'photo_2' ),
            'collector_date' => $trip->getAttribute( 'collector_date' ),
            'collector_time' => $trip->getAttribute( 'collector_time' ),
            'collector_signature' => $trip->getAttribute( 'collector_signature' ),
            'status' => $trip->getAttribute( 'delivery_status' ),
            'bin_liner_quantity' => $trip->getAttribute( 'bin_liner_quantity' ),
        ];
    }

    public function includeCompany( Trip $trip )
    {
        return $this->item( $trip->company, new CompanyTransformer, 'companies' );
    }
}
