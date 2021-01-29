<?php

namespace App\Transformers;

use App\Models\Feedback;
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
    protected $defaultIncludes = [];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'company','customer'
    ];

    /**
     * A Fractal transformer.
     *
     * @param Feedback $feedback
     * @return array
     */
    public function transform( Feedback $feedback ): array
    {
        return [
            'id' => $feedback->getAttribute( 'id' ),
            'message' => $feedback->getAttribute( 'message' ),
            'photo' => $this->getImageUrl( $feedback ),
            'company_id' => $feedback->company_id,
            'customer_name' => $feedback->customer->name,
            'phone_number' => $feedback->customer->phone_number,
            'customer_address' => $feedback->customer->address,
            'snoocode' => $feedback->customer->snoocode,
        ];
    }

    private function getImageUrl( Feedback $feedback )
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

    public function includeCompany( Feedback $feedback )
    {
        if ( is_null( $feedback->company ) ) return null;
        return $this->item( $feedback->company, new CompanyTransformer, 'companies' );
    }
}
