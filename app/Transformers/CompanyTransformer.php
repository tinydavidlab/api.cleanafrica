<?php

namespace App\Transformers;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes
        = [
            //
        ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes
        = [
            'customers', 'trips',
        ];

    /**
     * A Fractal transformer.
     *
     * @param Company $company
     *
     * @return array
     */
    public function transform( Company $company ): array
    {
        return [
            'id'           => $company->getAttribute( 'id' ),
            'name'         => $company->getAttribute( 'name' ),
            'logo'         => $this->getImageUrl( $company ),
            'tagline'      => $company->getAttribute( 'tagline' ),
            'email'        => $company->getAttribute( 'email' ),
            'phone_number' => $company->getAttribute( 'phone_number' ),
            'website'      => $company->getAttribute( 'website' ),
            'is_activated' => $company->getIsActivatedAttribute(),
            'activated_at' => $company->getAttribute( 'activated_at' ),
        ];
    }

    public function getImageUrl( Company $company ): ?string
    {
        if ( $company->getAttribute( 'logo' ) == null ) {
            return null;
        }
        return Storage::disk( 's3' )->url( 'companies/' . $company->getAttribute( 'logo' ) );
    }

    public function includeCustomers( Company $company ): Collection
    {
        return $this->collection( $company->customers, new CustomerTransformer, 'customers' );
    }

    public function includeTrips( Company $company ): Collection
    {
        return $this->collection( $company->trips, new TripTransformer, 'trips' );
    }
}
