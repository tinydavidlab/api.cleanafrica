<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\TruckRepository;
use App\Transformers\TruckTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class TruckController extends Controller
{
    /**
     * @var TruckRepository
     */
    private $repository;

    /**
     * TruckController constructor.
     * @param TruckRepository $repository
     */
    public function __construct( TruckRepository $repository )
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
        $trucks = $this->repository->all();
        $trucks = fractal( $trucks, new TruckTransformer )
            ->withResourceName( 'trucks' )
            ->toArray();

        return response()->json( [ 'trucks' => $trucks ], Response::HTTP_OK );
    }

    public function trucksForCompany(int $id)
    {
        $trucks = $this->repository->scopeQuery(function ($query) {
            return $query->orderBy('created_at','desc');
        })->getForCompany($id);
        $trucks = fractal($trucks, new TruckTransformer())
        ->withResourceName('trucks')
        ->toArray();
        return response()->json( [ 'trucks' => $trucks ], Response::HTTP_OK );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidatorException
     * @throws ValidationException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'name' => 'required',
            'license_number' => 'required'
        ] );

        $truck = $this->repository->create( $request->all() );
        if ( $request->hasFile( 'photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'trucks' ) );
            $this->repository->update( [ 'photo' => $filename ], $truck->id );
        }

        $truck = fractal( $truck, new TruckTransformer )
            ->withResourceName( 'trucks' )
            ->toArray();

        return response()->json( [ 'truck' => $truck ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        $truck = $this->repository->find( $id );
        $truck = fractal( $truck, new TruckTransformer )
            ->withResourceName( 'trucks' )
            ->toArray();

        return response()->json( [ 'truck' => $truck ], Response::HTTP_OK );
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
        $truck = $this->repository->update( $request->all(), $id );
        $truck = fractal( $truck, new TruckTransformer )
            ->withResourceName( 'trucks' )
            ->toArray();

        return response()->json( [ 'truck' => $truck ], Response::HTTP_OK );
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
        return response()->json( [], Response::HTTP_OK );
    }
}
