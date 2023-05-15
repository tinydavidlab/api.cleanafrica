<?php

namespace App\Transformers;

use App\Models\Agent;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param Agent $user
     * @return array
     */
    public function transform( Agent $user ): array
    {
        return [
            'id' => $user->getAttribute( 'id' ),
            'name' => $user->getAttribute( 'name' ),
            'type' => $user->getAttribute( 'type' ),
            'phone_number' => $user->getAttribute( 'phone_number' ),
            'address' => $user->getAttribute( 'address' ),
            'latitude' => $user->getAttribute( 'latitude' ),
            'property_photo' => $user->getAttribute( 'property_photo' ),
            'date_joined' => $user->getAttribute( 'created_at' ),
        ];
    }
}
