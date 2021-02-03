<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $repository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $repository
     */
    public function __construct( CategoryRepository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = $this->repository->orderBy( 'name' )->get();
        $categories = fractal( $categories, new CategoryTransformer )
            ->withResourceName( 'categories' )
            ->toArray();

        return response()->json( [ 'categories' => $categories ], Response::HTTP_OK );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException|ValidatorException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'name' => 'required',
            'parent' => 'required',
            'type' => 'required|in:support,general',
            'description' => 'required',
        ] );

        $category = $this->repository->create( $request->all() );
        $category = fractal( $category, new CategoryTransformer )
            ->withResourceName( 'categories' )
            ->toArray();

        return response()->json( [ 'category' => $category ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        $category = $this->repository->find( $id );
        $category = fractal( $category, new CategoryTransformer )
            ->withResourceName( 'categories' )
            ->toArray();

        return response()->json( [ 'category' => $category ], Response::HTTP_OK );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function update( Request $request, int $id ): JsonResponse
    {
        $category = $this->repository->update( $request->all(), $id );
        $category = fractal( $category, new CategoryTransformer )
            ->withResourceName( 'categories' )
            ->toArray();

        return response()->json( [ 'category' => $category ], Response::HTTP_OK );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy( int $id ): JsonResponse
    {
        $this->repository->delete( $id );

        return response()->json( [], Response::HTTP_NO_CONTENT );
    }
}
