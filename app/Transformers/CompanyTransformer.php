<?php

namespace App\Transformers;

use App\Models\Company;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
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
     * @param Company $company
     * @return array
     */
    public function transform( Company $company ): array
    {
        return [
            'id' => $company->getAttribute( 'id' ),
            'name' => $company->getAttribute( 'name' ),
            'logo' => $company->getAttribute( 'logo' ),
            'tagline' => $company->getAttribute( 'tagline' ),
            'email' => $company->getAttribute( 'email' ),
            'phone_number' => $company->getAttribute( 'phone_number' ),
            'website' => $company->getAttribute( 'website' ),
            'is_activated' => $company->getIsActivatedAttribute(),
            'activated_at' => $company->getAttribute( 'activated_at' ),
        ];
    }
}
