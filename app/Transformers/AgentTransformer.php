<?php

namespace App\Transformers;

use App\Models\Agent;
use League\Fractal\TransformerAbstract;

class AgentTransformer extends TransformerAbstract
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
     * @param Agent $agent
     *
     * @return array
     */
    public function transform( Agent $agent ): array
    {
        return [
            'id'           => $agent->getAttribute( 'id' ),
            'company_id'   => $agent->getAttribute( 'company_id' ),
            'name'         => $agent->getAttribute( 'name' ),
            'type'         => $agent->getAttribute( 'type' ),
            'phone_number' => $agent->getAttribute( 'phone_number' ),
            'can_optimise' => $agent->getAttribute( 'can_optimise' ),
            'activated_at' => $agent->getAttribute( 'activated_at' ),
        ];
    }

    public function includeCompany( Agent $agent )
    {
        return $this->item( $agent->company, new CompanyTransformer, 'companies' );
    }
}
