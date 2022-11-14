<?php

namespace App\Transformers;

use App\Models\Agent;
use League\Fractal\TransformerAbstract;

class AuthTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [

    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
    ];

    /**
     * A Fractal transformer.
     *
     * @param Agent $agent
     * @return array
     */
    public function transform( Agent $agent ): array
    {
        return [
            'id' => $agent->getAttribute( 'id' ),
            'name' => $agent->getAttribute( 'name' ),
            'type' => $agent->getAttribute( 'type' ),
            'phone_number' => $agent->getAttribute( 'phone_number' ),
            'activated_at' => $agent->getAttribute( 'activated_at' ),
        ];
    }

    public function includeCompany( Agent $agent )
    {
        return $this->item( $agent->company, new CompanyTransformer, 'companies' );
    }
}
