<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\TripRepository;
use App\Transformers\TripTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class CompletedTripController extends Controller
{
    /**
     * @var TripRepository
     */
    private $repository;

    /**
     * CompletedTripController constructor.
     * @param TripRepository $repository
     */
    public function __construct( TripRepository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidatorException
     * @throws ValidationException
     */
    public function store( Request $request, int $id ): JsonResponse
    {
        $this->validate( $request, [
            'bin_image' => 'required',
        ] );

        if ( $request->hasFile( 'bin_image' ) ) {
            $filename = ImageUploader::upload( $request->file( 'bin_image' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'bins' ) );
            $this->repository->update( [ 'bin_image' => $filename ], $id );
        }

        if ( $request->hasFile( 'property_image' ) ) {
            $filename = ImageUploader::upload( $request->file( 'property_image' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'properties' ) );
            $this->repository->update( [ 'property_image' => $filename ], $id );
        }

        $trip = $this->repository->update(
            [ 'delivery_status' => 'completed', 'notes' => $request->get( 'notes' ) ],
            $id
        );

        $trip = fractal( $trip->fresh(), new TripTransformer() )
            ->withResourceName( 'trips' )
            ->toArray();

        return response()->json( [ 'trip' => $trip ], Response::HTTP_OK );
    }
}
