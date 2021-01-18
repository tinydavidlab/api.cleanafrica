<?php

namespace App\Transformers;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
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
     * @param Customer $customer
     * @return array
     */
    public function transform( Customer $customer ): array
    {
        return [
            'id' => $customer->getAttribute( 'id' ),
            'name' => $customer->getAttribute( 'name' ),
            'address' => $customer->getAttribute( 'address' ),
            'snoocode' => $customer->getAttribute( 'snoocode' ),
            'latitude' => $customer->getAttribute( 'latitude' ),
            'longitude' => $customer->getAttribute( 'longitude' ),
            'division' => $customer->getAttribute( 'division' ),
            'subdivision' => $customer->getAttribute( 'subdivision' ),
            'country' => $customer->getAttribute( 'country' ),
            'phone_number' => $customer->getAttribute( 'phone_number' ),
            'property_photo' => $customer->getAttribute( 'property_photo' ),
            'apartment_number' => $customer->getAttribute( 'apartment_number' ),
            'date_joined' => $customer->getAttribute( 'created_at' ),
        ];
    }
}
