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
            'type' => $customer->getAttribute( 'type' ),
            'phone_number' => $customer->getAttribute( 'phone_number' ),
            'address' => $customer->getAttribute( 'address' ),
            'latitude' => $customer->getAttribute( 'latitude' ),
            'property_photo' => $customer->getAttribute( 'property_photo' ),
            'date_joined' => $customer->getAttribute( 'created_at' ),
        ];
    }
}
