<?php

namespace App\Transformers;

use App\Models\Customer;
use App\Utilities\ImageUploader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes
        = [
            'company',
        ];

    /**
     * A Fractal transformer.
     *
     * @param Customer $customer
     *
     * @return array
     */
    public function transform( Customer $customer ): array
    {
        return [
            'id'               => $customer->getAttribute( 'id' ),
            'company_id'       => $customer->getAttribute( 'company_id' ),
            'name'             => $customer->getAttribute( 'name' ),
            'address'          => $customer->getAttribute( 'address' ),
            'snoocode'         => $customer->getAttribute( 'snoocode' ),
            'latitude'         => $customer->getAttribute( 'latitude' ),
            'longitude'        => $customer->getAttribute( 'longitude' ),
            'division'         => $customer->getAttribute( 'division' ),
            'subdivision'      => $customer->getAttribute( 'subdivision' ),
            'country'          => $customer->getAttribute( 'country' ),
            'phone_number'     => $customer->getAttribute( 'phone_number' ),
            'property_photo'   => $this->getImageUrl( $customer ),
            'apartment_number' => $customer->getAttribute( 'apartment_number' ),
            'date_joined'      => Carbon::parse( $customer->getAttribute( 'created_at' ) )->format( 'd M Y H:i:s' ),
            'link'             => $customer->getLinkAttribute(),
        ];
    }

    private function getImageUrl( Customer $customer ): ?string
    {
        if ( $customer->getAttribute( 'property_photo' ) == null ) {
            return null;
        }

        if ( Str::startsWith( $customer->getAttribute( 'property_photo' ), 'http' ) ) {
            return $customer->getAttribute( 'property_photo' );
        }

        return ImageUploader::getFileURI( $customer->getAttribute( 'property_photo' ), 'properties' );
    }


    public function includeCompany( Customer $admin ): ?Item
    {
        if ( is_null( $admin->company ) ) return null;
        return $this->item( $admin->company, new CompanyTransformer, 'companies' );
    }
}
