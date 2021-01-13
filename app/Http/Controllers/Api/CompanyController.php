<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\CompanyRepository;
use App\Transformers\CompanyTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    /**
     * @var CompanyRepository
     */
    private $repository;

    /**
     *
     * CompanyController constructor.
     *
     * @param CompanyRepository $repository
     */
    public function __construct( CompanyRepository $repository )
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
        $companies = $this
            ->repository
            ->orderBy( 'created_at', 'desc' )
            ->all();

        $companies = fractal( $companies, new CompanyTransformer() )
            ->withResourceName( 'companies' )
            ->toArray();

        return response()->json( [ 'companies' => $companies ], Response::HTTP_OK );
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
        $this->validate( $request, [
            'name' => 'required',
            'logo' => 'image'
        ] );

        $company = $this->repository->create( $request->except( 'logo' ) );

        if ( $request->hasFile( 'logo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'logo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'companies' ) );
            $this->repository->update( [ 'logo' => $filename ], $company->id );
        }

        $company = fractal( $company->fresh(), new CompanyTransformer() )
            ->withResourceName( 'companies' )
            ->toArray();

        return response()->json( [ 'company' => $company ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        $company = $this->repository->find( $id );
        $company = fractal( $company, new CompanyTransformer() )
            ->withResourceName( 'companies' )
            ->toArray();

        return response()->json( [ 'company' => $company ], Response::HTTP_OK );
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
            $company = $this->repository->find( $id );

            if ( $request->hasFile( 'logo' ) ) {
                $filename = ImageUploader::update(
                    $request->file( 'logo' ),
                    $company->getAttribute( 'logo' ), 'companies' );
                $this->dispatch( new ProcessImageUpload( $filename, 'companies' ) );
                $this->repository->update( [ 'logo' => $filename ], $company->id );
            }

            $this->repository->update( $request->except( 'logo' ), $id );

            $company = fractal( $company->fresh(), new CompanyTransformer() )
                ->withResourceName( 'companies' )
                ->toArray();

            return response()->json( [ 'company' => $company ], Response::HTTP_OK );
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
                [ 'message' => 'No company was found with: ' . $id ], Response::HTTP_NOT_FOUND );
        }

        return response()->json( [], Response::HTTP_NO_CONTENT );
    }
}
