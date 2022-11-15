<?php

namespace App\Transformers;

use App\Models\Admin;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
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
     * @param Admin $admin
     *
     * @return array
     */
    public function transform( Admin $admin ): array
    {
        return [
            'id'           => $admin->getAttribute( 'id' ),
            'name'         => $admin->getAttribute( 'name' ),
            'company_id'   => $admin->getAttribute( 'company_id' ),
            'phone_number' => $admin->getAttribute( 'phone_number' ),
            'type'         => $admin->getAttribute( 'type' ),
        ];
    }

    public function includeCompany( Admin $admin ): ?Item
    {
        if ( is_null( $admin->company ) ) return null;
        return $this->item( $admin->company, new CompanyTransformer, 'companies' );
    }
}
