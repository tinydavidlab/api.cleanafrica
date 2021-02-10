<?php

namespace App\Http\Controllers\Api;

use App\Filters\TripFilter;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\TripRepository;
use App\Transformers\TripTransformer;
use App\Utilities\ImageUploader;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class TripController extends Controller
{
    /**
     * @var TripRepository
     */
    private $repository;

    /**
     * TripController constructor.
     * @param TripRepository $repository
     */
    public function __construct( TripRepository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param TripFilter $filter
     * @return JsonResponse
     */
    public function index( TripFilter $filter ): JsonResponse
    {
        $trips = $this->repository->filter( $filter )->get();

        $trips = fractal( $trips, new TripTransformer() )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trips' => $trips ], Response::HTTP_OK );
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
            'customer_name' => 'required',
            'customer_primary_phone_number' => 'required',
            'customer_apartment_number' => 'required',
            'customer_country' => 'required',
            'customer_division' => 'required',
            'customer_subdivision' => 'required',
            'customer_snoocode' => 'required',
            'customer_latitude' => 'required',
            'customer_longitude' => 'required',
        ] );

        $trip = $this->repository->create([ 'bin_image', 'property_image']);

        if ( $request->hasFile( 'bin_image' ) ) {
            $filename = ImageUploader::upload( $request->file( 'bin_image' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'bins' ) );
            $this->repository->update( [ 'bin_image' => $filename ], $trip->id );
        }

        if ( $request->hasFile( 'property_image' ) ) {
            $filename = ImageUploader::upload( $request->file( 'property_image' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'properties' ) );
            $this->repository->update( [ 'property_image' => $filename ], $trip->id );
        }

        $trip = fractal( $trip->fresh(), new TripTransformer() )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trip' => $trip ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        $trip = $this->repository->find( $id );

        $trip = fractal( $trip, new TripTransformer() )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trip' => $trip ], Response::HTTP_OK );

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
        $trip = $this->repository->update( $request->except( [ 'bin_image', 'property_image' ] ), $id );
        $trip = fractal( $trip, new TripTransformer() )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trip' => $trip ], Response::HTTP_OK );
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
