<?php

namespace App\Transformers;

use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class FeedbackTransformer extends TransformerAbstract
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
    protected array $availableIncludes = [
        'company', 'customer'
    ];

    /**
     * A Fractal transformer.
     *
     * @param Feedback $feedback
     *
     * @return array
     */
    public function transform( Feedback $feedback ): array
    {
        $stamp = json_decode( $feedback->stamp, true );
        return [
            'id' => $feedback->getAttribute( 'id' ),
            'company_id' => $feedback->company_id,
            'message' => $feedback->getAttribute( 'message' ),
            'photo' => $this->getImageUrl( $feedback ),
            'customer_name' => $feedback->customer->name,
            'phone_number' => $feedback->customer->phone_number,
            'customer_address' => $feedback->customer->address,
            'app_version' => $feedback->getAttribute( 'app_version' ),
            'user_agent' => $feedback->getAttribute( 'user_agent' ),
            'device_id' => $feedback->getAttribute( 'device_id' ),
            'manufacturer' => $feedback->getAttribute( 'manufacturer' ),
            'brand' => $feedback->getAttribute( 'brand' ),
            'model' => $feedback->getAttribute( 'model' ),
            'received_at' => Carbon::parse( $feedback->getAttribute( 'created_at' ) )->format( 'd M Y H:i:s' ),
            'signature' => Arr::get( $stamp, 'stamp_string' ),
            'country' => Arr::get( $stamp, 'country' ),
            'subdivision' => Arr::get( $stamp, 'subdivision' ),
            'division' => Arr::get( $stamp, 'division' ),
            'altitude' => Arr::get( $stamp, 'altitude' ),
            'date' => Arr::get( $stamp, 'date' ),
            'day' => Arr::get( $stamp, 'day' ),
            'time' => Arr::get( $stamp, 'time' ),
            'extension' => Arr::get( $stamp, 'extension' ),
            'key' => Arr::get( $stamp, 'key' ),
            'latitude' => Arr::get( $stamp, 'latitude' ),
            'latitude_number' => Arr::get( $stamp, 'latitudeNumber' ),
            'longitude' => Arr::get( $stamp, 'longitude' ),
            'longitude_number' => Arr::get( $stamp, 'longitudeNumber' ),
            'snoocode' => Arr::get( $stamp, 'code' ),
        ];
    }

    private function getImageUrl( Feedback $feedback ): ?string
    {
        if ( $feedback->getAttribute( 'photo' ) == null ) {
            return null;
        }

        return Storage::disk( 's3' )->url( 'feedback/' . $feedback->getAttribute( 'photo' ) );
    }

    public function includeCustomer( Feedback $feedback ): Item
    {
        return $this->item( $feedback->customer, new CustomerTransformer, 'customers' );
    }

    public function includeCompany( Feedback $feedback ): ?Item
    {
        if ( is_null( $feedback->company ) ) return null;
        return $this->item( $feedback->company, new CompanyTransformer, 'companies' );
    }
}
