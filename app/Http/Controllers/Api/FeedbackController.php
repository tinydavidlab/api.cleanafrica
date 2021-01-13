<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\FeedbackRepository;
use App\Transformers\FeedbackTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class FeedbackController extends Controller
{
    /**
     * @var FeedbackRepository
     */
    private $repository;

    /**
     * FeedbackController constructor.
     * @param FeedbackRepository $repository
     */
    public function __construct( FeedbackRepository $repository )
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
        $feedback = $this->repository->all();
        $feedback = fractal( $feedback, new FeedbackTransformer )
            ->withResourceName( 'feedback' )
            ->toArray();

        return response()->json( [ 'feedback' => $feedback ], Response::HTTP_OK );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function store( Request $request ): JsonResponse
    {
        $feedback = $this->repository->create( $request->except( 'photo' ) );
        if ( $request->hasFile( 'photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'feedback' ) );
            $this->repository->update( [ 'photo' => $filename ], $feedback->id );
        }

        $feedback = fractal( $feedback->fresh(), new FeedbackTransformer )
            ->withResourceName( 'feedback' )
            ->toArray();

        return response()->json( [ 'agent' => $feedback ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        $feedback = $this->repository->find( $id );
        $feedback = fractal( $feedback, new FeedbackTransformer )
            ->withResourceName( 'feedback' )
            ->toArray();

        return response()->json( [ 'agent' => $feedback ], Response::HTTP_OK );
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
        $feedback = $this->repository->update( $request->except( 'photo' ), $id );
        $feedback = fractal( $feedback, new FeedbackTransformer )
            ->withResourceName( 'feedback' )
            ->toArray();

        return response()->json( [ 'agent' => $feedback ], Response::HTTP_OK );
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

