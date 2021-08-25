<?php

namespace App\Transformers;

use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class TripTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'company', 'truck', 'collector', 'customer'
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
            'id'                              => $trip->getAttribute( 'id' ),
            'company_id'                      => $trip->getAttribute( 'company_id' ),
            'truck_id'                        => $trip->getAttribute( 'truck_id' ),
            'order'                           => $trip->getAttribute( 'order' ),
            'customer_name'                   => $trip->getAttribute( 'customer_name' ),
            'customer_primary_phone_number'   => $trip->getAttribute( 'customer_primary_phone_number' ),
            'customer_secondary_phone_number' => $trip->getAttribute( 'customer_secondary_phone_number' ),
            'customer_apartment_number'       => $trip->getAttribute( 'customer_apartment_number' ),
            'customer_country'                => $trip->getAttribute( 'customer_country' ),
            'customer_division'               => $trip->getAttribute( 'customer_division' ),
            'customer_subdivision'            => $trip->getAttribute( 'customer_subdivision' ),
            'customer_snoocode'               => $trip->getAttribute( 'customer_snoocode' ),
            'customer_longitude'              => $trip->getAttribute( 'customer_longitude' ),
            'customer_latitude'               => $trip->getAttribute( 'customer_latitude' ),
            'customer_latitude_number'        => $trip->getAttribute( 'customer_latitude_number' ),
            'customer_longitude_number'       => $trip->getAttribute( 'customer_longitude_number' ),

            'collector_name'        => $trip->getAttribute( 'collector_name' ),
            'collector_country'     => $trip->getAttribute( 'collector_country' ),
            'collector_division'    => $trip->getAttribute( 'collector_division' ),
            'collector_subdivision' => $trip->getAttribute( 'collector_subdivision' ),
            'collector_snoocode'    => $trip->getAttribute( 'collector_snoocode' ),
            'collector_date'        => $trip->getAttribute( 'collector_date' ),
            'collector_time'        => $trip->getAttribute( 'collector_time' ),
            'collector_signature'   => $trip->getAttribute( 'collector_signature' ),

            'bin_image'          => $this->getImageUrl( $trip->getAttribute( 'bin_image' ), 'bins' ),
            'property_photo'     => $this->getImageUrl( $trip->getAttribute( 'property_image' ), 'properties' ),
            'status'             => $trip->getAttribute( 'delivery_status' ),
            'assigned_to'        => $trip->getAttribute( 'assigned_to' ),
            'bin_liner_quantity' => $trip->getAttribute( 'bin_liner_quantity' ),
            'notes'              => $trip->getAttribute( 'notes' ),
            'link'               => $trip->getLinkAttribute(),
            'created_at'         => Carbon::parse( $trip->getAttribute( 'created_at' ) )->format( 'l, d F Y @ H:i:s' ),
        ];
    }

    /**
     * @param string|null $image
     * @param string $folder
     * @return string|null
     */
    private function getImageUrl( ?string $image, string $folder ): ?string
    {
        if ( $image == null ) return null;

        return Storage::disk( 's3' )->url( $folder . '/' . $image );
    }

    /**
     * @param Trip $trip
     * @return Item|null
     */
    public function includeCompany( Trip $trip ): ?Item
    {
        if ( !$trip->company ) return null;
        return $this->item( $trip->company, new CompanyTransformer, 'companies' );
    }

    /**
     * @param Trip $trip
     * @return Item|null
     */
    public function includeTruck( Trip $trip ): ?Item
    {
        if ( !$trip->truck ) return null;

        return $this->item( $trip->truck, new TruckTransformer(), 'trucks' );
    }

    /**
     * @param Trip $trip
     * @return Item|null
     */
    public function includeCustomer( Trip $trip ): ?Item
    {
        if ( !$trip->customer ) return null;

        return $this->item( $trip->customer, new CustomerTransformer(), 'customers' );
    }

    /**
     * @param Trip $trip
     * @return Item|null
     */
    public function includeCollector( Trip $trip ): ?Item
    {
        if ( !$trip->collector ) return null;

        return $this->item( $trip->collector, new AgentTransformer(), 'collectors' );
    }
}
