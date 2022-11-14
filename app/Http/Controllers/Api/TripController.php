<?php

namespace App\Http\Controllers\Api;

use App\Filters\TripFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTripRequest;
use App\Jobs\ProcessImageUpload;
use App\Models\Trip;
use App\Repositories\TripRepository;
use App\Transformers\TripTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @param CreateTripRequest $request
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function store( CreateTripRequest $request ): JsonResponse
    {
        $trip = $this->repository->create( $request->except( [ 'bin_image', 'property_image' ] ) );

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

    /**
     * @param $date
     * @return JsonResponse
     */
    public function getTripsPerDate( $date ): JsonResponse
    {
        $trips = $this->repository->getTripsPerDate( $date );

        $trips = fractal( $trips, new TripTransformer() )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trips' => $trips ], Response::HTTP_OK );
    }

    /**
     * @return JsonResponse
     */
    public function getTripsForThisWeek(): JsonResponse
    {
        $trips = $this->repository->scopeQuery( function ( $query ) {
            return $query->latest();
        } )->getAllTripsForThisWeek();
        $trips = fractal( $trips, new TripTransformer() )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trips' => $trips ], Response::HTTP_OK );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function assignMultipleTrucksToTrips( Request $request )
    {
        $ids = $request[ 'trip_ids' ];
        Trip::whereIn( 'id', $ids )->update( [ 'truck_id' => $request[ 'truck_id' ] ] );
        return response()->json( [], Response::HTTP_NO_CONTENT );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function optimiseTrips( Request $request ): JsonResponse
    {
        $trips = $request->all();

        foreach ( $trips as $trip ) {
            Trip::where( 'id', $trip[ 'id' ] )->update(
                [
                    'order'                     => $trip[ 'order' ],
                    'customer_apartment_number' => $trip[ 'customer_apartment_number' ]
                ]
            );
        }

        return response()->json( [ "message" => "Optimised trips uploaded successfully" ], Response::HTTP_OK );
    }
}
