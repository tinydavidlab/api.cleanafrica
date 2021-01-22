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
        'company'
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'company'
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
            'company_id' => $customer->getAttribute( 'company_id' ),
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

    public function includeCompany( Customer $admin )
    {
        if ( is_null( $admin->company ) ) return null;
        return $this->item( $admin->company, new CompanyTransformer, 'companies' );
    }
}
