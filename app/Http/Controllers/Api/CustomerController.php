<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\CustomerRepository;
use App\Transformers\CustomerTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @var CustomerRepository
     */
    private $repository;

    /**
     * CustomerController constructor.
     * @param CustomerRepository $repository
     */
    public function __construct( CustomerRepository $repository )
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
        $customers = $this->repository->orderBy( 'created_at', 'desc' )->all();

        $customers = fractal( $customers, new CustomerTransformer() )
            ->withResourceName( 'customers' )
            ->toArray();

        return response()->json( [ 'customers' => $customers ], Response::HTTP_OK );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidatorException|ValidationException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [ 'property_photo' => 'image' ] );
        $customer = $this->repository->create( $request->except( 'property_photo' ) );

        if ( $request->hasFile( 'property_photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'property_photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'properties' ) );
            $this->repository->update( [ 'property_photo' => $filename ], $customer->id );
        }

        $customer = fractal( $customer->fresh(), new CustomerTransformer() )
            ->withResourceName( 'customers' )
            ->toArray();

        return response()->json( [ 'customer' => $customer ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        $customer = $this->repository->find( $id );

        $customer = fractal( $customer, new CustomerTransformer() )
            ->withResourceName( 'customers' )
            ->toArray();

        return response()->json( [ 'customer' => $customer ], Response::HTTP_OK );
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
        try {
            $customer = $this->repository->update( $request->except( 'property_photo' ), $id );
            $customer = fractal( $customer->fresh(), new CustomerTransformer() )
                ->withResourceName( 'customers' )
                ->toArray();

            return response()->json( [ 'customer' => $customer ], Response::HTTP_OK );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json(
                [ 'message' => 'No company was found with: ' . $id ], Response::HTTP_NOT_FOUND );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy( int $id ): JsonResponse
    {
        try {
            $this->repository->delete( $id );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json(
                [ 'message' => 'No customer was found with: ' . $id ], Response::HTTP_NOT_FOUND );
        }

        return response()->json( [], Response::HTTP_NO_CONTENT );
    }
}
