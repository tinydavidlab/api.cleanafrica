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
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class CompanyCustomerController extends Controller
{
    private $repository;

    public function __construct(CustomerRepository  $repository)
    {
        $this->repository = $repository;
    }

    public function index(int $id)
    {
        $customers = $this->repository->getForCompany($id);
        $customers = fractal($customers, new CustomerTransformer())
        ->withResourceName('customers')
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
    public function store( Request $request, int $id ): JsonResponse
    {
        $this->validate( $request, [
            'name' => 'required',
            'phone_number' => 'required|unique:customers',
            'country' => 'required',
            'division' => 'required',
            'subdivision' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'property_photo' => 'image'
        ] );

        $customer = $this->repository->create(
            array_merge(
                $request->except( [ 'property_photo', 'password' ] ),
                [
                    'password' => Hash::make( $request->get( 'phone_number' ) ),
                ]
            ) );

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


    public function show(int $id, int $customer_id): JsonResponse
    {
        try {
            $customer = $this->repository->findForCompany($id, $customer_id);
            $customer = fractal($customer, new CustomerTransformer())
            ->withResourceName('customers')
            ->toArray();

            return response()->json( [ 'customer' => $customer ], Response::HTTP_OK );

        } catch (ModelNotFoundException $exception) {
            return response()->json( [ 'message' => 'No customer was found with id: ' . $customer_id ], Response::HTTP_NOT_FOUND );
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $agent_id
     * @return JsonResponse
     */
    public function destroy( int $id, int $customer_id ): JsonResponse
    {
        try {
            $this->repository->delete( $customer_id );

            return response()->json( [], Response::HTTP_NO_CONTENT );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json(
                [ 'message' => 'No customer was found with: ' . $id ], Response::HTTP_NOT_FOUND );
        }
    }

}
