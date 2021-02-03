<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
    protected $availableIncludes = [];

    /**
     * A Fractal transformer.
     *
     * @param Category $category
     *
     * @return array
     */
    public function transform( Category $category ): array
    {
        return [
            'id' => $category->getAttribute( 'id' ),
            'type' => $category->getAttribute( 'type' ),
            'name' => $category->getAttribute( 'name' ),
            'parent' => $category->getAttribute( 'parent' ),
            'description' => $category->getAttribute( 'description' ),
        ];
    }
}
