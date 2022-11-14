<?php

namespace App\Transformers;

use App\Models\Truck;
use League\Fractal\TransformerAbstract;

class TruckTransformer extends TransformerAbstract
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
        'company'
    ];

    /**
     * A Fractal transformer.
     *
     * @param Truck $truck
     * @return array
     */
    public function transform( Truck $truck ): array
    {
        return [
            'id' => $truck->getAttribute( 'id' ),
            'company_id' => $truck->company->id,
            'name' => $truck->getAttribute( 'name' ),
            'license_number' => $truck->getAttribute( 'license_number' ),
        ];
    }

    public function includeCompany( Truck $truck )
    {
        return $this->item( $truck->company, new CompanyTransformer(), 'companies' );
    }
}
