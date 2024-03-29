<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\CustomerRepository;
use App\Transformers\CustomerTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use function MongoDB\BSON\toJSON;

class PropertyRegistrationController extends Controller
{
    /**
     * @var CustomerRepository
     */
    private $repository;

    /**
     * PropertyRegistrationController constructor.
     *
     * @param CustomerRepository $repository
     */
    public function __construct( CustomerRepository $repository )
    {
        $this->repository = $repository;
    }

    /**
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException|ValidatorException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'property_photo' => 'required',
            'customer_id' => 'required',
        ] );

        $customerId = $request->customer_id;

        if ( $request->hasFile( 'property_photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'property_photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'properties' ) );
//            error_log(auth()->user()->address);
//            Log::debug(auth()->id());
            $this->repository->update( [ 'property_photo' => $filename ], $customerId );
        }

        $customer = $this->repository->find( $customerId );

        $customer = fractal( $customer, new CustomerTransformer() )
            ->withResourceName( 'customers' )
            ->toArray();

        return response()->json( [ 'customer' => $customer ], Response::HTTP_OK );
    }

    /**
     * Check if property photo exists.
     *
     * @return JsonResponse
     */
    public function check( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'customer_id' => 'required',
        ] );

        $customer      = $this->repository->find( $request->customer_id);
        $propertyPhoto = $customer->property_photo;

        $checker = false;
        if ( !is_null( $propertyPhoto ) ) {
            $checker = true;
        }

//        $checker = auth()->check() ? (bool)auth()->user()->property_photo : true;
        return response()->json( [
            'property_photo_exists' => $checker,
        ] );
    }
}
