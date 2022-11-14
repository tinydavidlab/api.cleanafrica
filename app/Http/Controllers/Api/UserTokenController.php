<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserTokenController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [ 'device_token' => 'required', 'guard' => 'required:in:customer,admin,super_admin,collector' ] );

        $request
            ->user( $request->get( 'guard' ) )
            ->update( [ 'device_token' => $request->get( 'device_token' ) ] );

        return response()->json( [ 'message' => 'User device token registered successfully!' ], Response::HTTP_OK );
    }
}
