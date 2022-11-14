<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Transformers\CustomerTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'phone_number' => 'required',
        ] );

        $customer = Customer::wherePhoneNumber( $request->get( 'phone_number' ) )
            ->first();
        if ( !$customer ) return response()->json(
            [ 'message' => 'The user credential is invalid.' ],
            Response::HTTP_UNAUTHORIZED
        );

        return response()->json( [
            'token' => encrypt( $customer->getAttribute( 'id' ) ),
            'customer' => fractal( $customer->fresh(), new CustomerTransformer() )
                ->withResourceName( 'customers' )->toArray()
        ], Response::HTTP_OK );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function new( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'name' => 'required',
            'type' => 'required',
            'phone_number' => 'required',
        ] );

        $customer = Customer::firstOrCreate(
            [
                'name' => $request->get( 'name' ),
                'phone_number' => $request->get( 'phone_number' ),
                'type' => $request->get( 'type' ),
            ]
        );

        $customer = fractal( $customer->fresh(), new CustomerTransformer() )
            ->withResourceName( 'customers' )
            ->toArray();

        return response()->json( [
            'customer' => $customer,
            'token' => encrypt( Arr::get( $customer, 'id' ) )
        ], Response::HTTP_CREATED );

    }
}
