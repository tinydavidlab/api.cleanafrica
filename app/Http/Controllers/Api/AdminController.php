<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use App\Transformers\AdminTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @var AdminRepository
     */
    private $repository;

    /**
     * AdminController constructor.
     *
     * @param AdminRepository $repository
     */
    public function __construct( AdminRepository $repository )
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
        $admins = $this->repository->all();
        $admins = fractal( $admins, new AdminTransformer )
            ->withResourceName( 'admins' )
            ->toArray();

        return response()->json( [ 'admins' => $admins ], Response::HTTP_OK );
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
            'phone_number' => 'required',
            'type' => 'required',
        ] );

        $admin = $this->repository->create( $request->all() );
        $admin = fractal( $admin, new AdminTransformer )
            ->withResourceName( 'admins' )
            ->toArray();

        return response()->json( [ 'admin' => $admin ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        $admin = $this->repository->find( $id );
        $admin = fractal( $admin, new AdminTransformer )
            ->withResourceName( 'admins' )
            ->toArray();

        return response()->json( [ 'admin' => $admin ], Response::HTTP_OK );
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
        $admin = $this->repository->update( $request->all(), $id );
        $admin = fractal( $admin, new AdminTransformer )
            ->withResourceName( 'admins' )
            ->toArray();

        return response()->json( [ 'admin' => $admin ], Response::HTTP_OK );
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
