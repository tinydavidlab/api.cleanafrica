<?php

namespace App\Transformers;

use App\Models\Admin;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param Admin $admin
     * @return array
     */
    public function transform( Admin $admin ): array
    {
        return [
            'id' => $admin->getAttribute( 'id' ),
            'name' => $admin->getAttribute( 'name' ),
            'phone_number' => $admin->getAttribute( 'phone_number' ),
        ];
    }
}
