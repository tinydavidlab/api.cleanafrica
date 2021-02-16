<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CompanyCategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CompanyCategoryController constructor.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct( CategoryRepository $categoryRepository )
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index( int $id ): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoriesForCompany( $id );

        $categories = fractal( $categories, new CategoryTransformer )
            ->withResourceName( 'categories' )
            ->toArray();

        return response()->json( [ 'categories' => $categories ], Response::HTTP_OK );
    }
}
