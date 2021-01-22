<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\TripRepository;
use App\Transformers\TripTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class CompanyTripController extends Controller
{
    /**
     * @var TripRepository
     */
    private $repository;

    /**
     * CompanyTripController constructor.
     * @param TripRepository $repository
     */
    public function __construct( TripRepository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function index( int $id ): JsonResponse
    {
        $trips = $this->repository->getForCompany( $id );

        $trips = fractal( $trips, new TripTransformer )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trips' => $trips ], Response::HTTP_OK );
    }

    public function getCompanyTripsPerStatus(int $id, $status, $date): JsonResponse
    {
        $trips = $this->repository->scopeQuery(function($query){
            return $query->orderBy('created_at','desc');
        })->getTripsForCompany($id, $status, $date);

        $trips = fractal( $trips, new TripTransformer )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trips' => $trips ], Response::HTTP_OK );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws ValidatorException
     */
    public function store( int $id, Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'customer_name' => 'required',
            'customer_primary_phone_number' => 'required',
            'customer_secondary_phone_number' => 'required',
            'customer_apartment_number' => 'required',
            'customer_country' => 'required',
            'customer_division' => 'required',
            'customer_subdivision' => 'required',
            'customer_snoocode' => 'required',
            'delivery_status' => 'required',
            'collection_date' => 'required',
            'collector_country' => 'required',
            'collector_division' => 'required',
            'collector_subdivision' => 'required',
            'collector_snoocode' => 'required',
            'collector_date' => 'required',
            'collector_time' => 'required',
            'collector_signature' => 'required',
        ] );

        $trip = $this->repository->create(
            array_merge( $request->except( [ 'bin_image', 'property_image' ] ), [ 'company_id' => $id ] )
        );

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

        $trip = fractal( $trip, new TripTransformer )
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

        $trip = fractal( $trip, new TripTransformer )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trip' => $trip ], Response::HTTP_OK );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        //
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
                [ 'message' => 'No trip was found with: ' . $id ], Response::HTTP_NOT_FOUND );
        }
    }
}
