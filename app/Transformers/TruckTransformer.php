<?php

namespace App\Transformers;

use App\Models\Truck;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;

class TruckTransformer extends TransformerAbstract
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
        'company', 'agents'
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
            'assigned_to' => $this->getAssignedTo($truck),
        ];
    }

    public function includeCompany( Truck $truck )
    {
        return $this->item( $truck->company, new CompanyTransformer(), 'companies' );
    }

    public function includeAgents( Truck $truck ): Collection
    {
        return $this->collection( $truck->agents, new AgentTransformer, 'agents' );
    }

    public function getAssignedTo(Truck $truck)
    {
        return  $truck->agents->first() ? $truck->agents->first()->name : null;
    }
}
