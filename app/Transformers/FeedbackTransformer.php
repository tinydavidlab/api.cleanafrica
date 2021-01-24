<?php

namespace App\Transformers;

use App\Models\Feedback;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class FeedbackTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'customer'
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
        'company'
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
            'photo' => $feedback->getAttribute( 'photo' ),
            'company_id' => $feedback->company->id,
        ];
    }

    public function includeCustomer( Feedback $feedback ): Item
    {
        return $this->item( $feedback->customer, new CustomerTransformer, 'customers' );
    }

    public function includeCompany(Feedback $feedback)
    {
        if ( is_null( $feedback->company ) ) return null;
        return $this->item($feedback->company, new CompanyTransformer, 'companies');
    }
}
