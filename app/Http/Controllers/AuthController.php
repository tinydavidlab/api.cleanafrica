<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use App\Repositories\CustomerRepository;
use App\Transformers\AdminTransformer;
use App\Transformers\CustomerTransformer;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    /**
     * @var AdminRepository
     */
    private $adminRepository;

    /**
     * AuthController constructor.
     * @param CustomerRepository $customerRepository
     * @param AdminRepository $adminRepository
     */
    public function __construct( CustomerRepository $customerRepository, AdminRepository $adminRepository )
    {
        $this->customerRepository = $customerRepository;
        $this->adminRepository    = $adminRepository;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'username' => 'required',
            'type' => 'required|in:customer,collector,admin',
        ] );

        $login_type = filter_var( $request->get( 'username' ), FILTER_VALIDATE_EMAIL ) ? 'email' : 'phone_number';

        $credentials = [
            $login_type => $request->get( 'username' ),
            'password' => $request->get( 'username' ),
            'type' => $request->get( 'type' )
        ];

        if ( !$token = auth()->guard( $request->get( 'type' ) )->attempt( $credentials ) ) {
            return response()->json( [ 'message' => 'Unauthorized' ], Response::HTTP_UNAUTHORIZED );
        }

        return $this->respondWithToken( $request->get( 'type' ), $token );
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $guard = request( 'type' );
        $me    = fractal( auth()->guard( $guard )->user(), $guard == 'customer' ? new CustomerTransformer : new AdminTransformer )->withResourceName( Str::plural( $guard ) )->toArray();
        return response()->json( [ 'me' => $me ] );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json( [ 'message' => 'Successfully logged out' ] );
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken( auth()->refresh() );
    }

    /**
     * Get the token array structure.
     *
     * @param string $type
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken( string $type, string $token ): JsonResponse
    {
        $token = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Carbon::now()->addYear()->timestamp,
        ];

        return response()->json( [
            'auth' => [
                'token' => $token,
                $type => fractal( auth()->guard( $type )->user(), $type == 'customer' ? new CustomerTransformer : new AdminTransformer )->withResourceName( Str::plural( $type ) )->toArray()
            ]
        ] );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws ValidatorException
     */
    public function register( Request $request ): JsonResponse
    {
        $table = Str::plural( $request->get( 'type', 'customer' ) );
        $table = $table == "collectors" ? "admins" : $table;

        $this->validate( $request, [
            'name' => 'required',
            'type' => 'required|in:customer,collector,admin',
            'phone_number' => 'required|unique:' . $table . ',phone_number',
            'company_id' => 'exists:companies,id'
        ] );

        $this->createUserWithType( $request->all(), $request->get( 'type' ) );

        $credentials = [
            'phone_number' => $request->get( 'phone_number' ),
            'password' => $request->get( 'phone_number' ),
            'company_id' => $request->get( 'company_id' ),
        ];

        if ( !$token = auth()->guard( $request->get( 'type' ) )->attempt( $credentials ) ) {
            return response()->json( [ 'message' => 'Unauthorized' ], Response::HTTP_UNAUTHORIZED );
        }

        return $this->respondWithToken( $request->get( 'type' ), $token );
    }

    /**
     * @param array $data
     * @param string $type
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    private function createUserWithType( array $data, string $type )
    {
        if ( $type == 'customer' ) {
            return $this->customerRepository->create(
                array_merge(
                    $data,
                    [ 'password' => Hash::make( $data[ 'phone_number' ] ),
                        'snoocode' => $data[ 'code' ] ]
                )
            );
        }

        return $this->adminRepository->create(
            array_merge(
                $data,
                [ 'password' => Hash::make( $data[ 'phone_number' ] ) ]
            )
        );
    }
}
