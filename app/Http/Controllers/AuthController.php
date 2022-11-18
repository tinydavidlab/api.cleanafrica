<?php

namespace App\Http\Controllers;

use App\Events\NewUserRegistered;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Customer;
use App\Repositories\AdminRepository;
use App\Repositories\AgentRepository;
use App\Repositories\CustomerRepository;
use App\Transformers\AdminTransformer;
use App\Transformers\AgentTransformer;
use App\Transformers\CustomerTransformer;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Client;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     *
     * @param CustomerRepository $customerRepository
     * @param AdminRepository    $adminRepository
     * @param AgentRepository    $agentRepository
     */
    public function __construct( public CustomerRepository $customerRepository,
                                 public AdminRepository    $adminRepository,
                                 public AgentRepository    $agentRepository )
    {
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function login( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'username'      => 'required',
            'grant_type'    => 'in:password',
            'client_id'     => 'required',
            'client_secret' => 'required',
            'type'          => 'in:customer,collector,admin,super_admin',
        ] );

        $type = $request->get( 'type', 'admin' );

        $credentials = [
            'grant_type'    => $request->get( 'grant_type', 'password' ),
            'username'      => $request->get( 'username' ),
            'password'      => $request->get( 'username' ),
            'client_id'     => $request->get( 'client_id' ),
            'client_secret' => $request->get( 'client_secret' ),
            'scope'         => $request->get( 'scope', '' ),
        ];

        $request    = Request::create( '/oauth/token', 'POST', $credentials );
        $response   = app()->handle( $request );
        $auth_token = json_decode( $response->getContent(), true );
        if ( $response->getStatusCode() != Response::HTTP_OK ) {
            return response()->json( [
                'message' => Arr::get( $auth_token, 'message' ),
            ], Response::HTTP_BAD_REQUEST );
        }

        $user = $this->getUserForGrantType( $type, $auth_token );

        return response()->json( [
            'auth' => [
                'token' => $auth_token,
                $type   => $this->getTransformedUserType( $user, $type ),
            ],
        ], Response::HTTP_OK );
    }

    protected function getUserForGrantType( string $type, array $auth_token )
    {
        $access_token = Arr::get( $auth_token, 'access_token' );

        if ( !$jwt = $access_token ) return null;
        [ $headb64, $bodyb64, $cryptob64 ] = explode( '.', $jwt );
        $body = JWT::jsonDecode( JWT::urlsafeB64Decode( $bodyb64 ) );
        if ( !$id = data_get( $body, 'sub' ) ) return null;

        if ( $type == 'customer' ) {
            return Customer::find( $id );
        } elseif ( $type == 'collector' ) {
            return Agent::find( $id );
        }

        return Admin::find( $id );
    }

    private function getTransformedUserType( $user, $type = 'admin' ): array
    {
        if ( $type == 'collector' ) {
            $user = fractal( $user, new AgentTransformer() )
                ->withResourceName( Str::plural( $type ) )
                ->toArray();
        } else if ( $type == 'customer' ) {
            $user = fractal( $user, new CustomerTransformer() )
                ->withResourceName( Str::plural( $type ) )
                ->toArray();
        } else {
            $user = fractal( $user, new AdminTransformer() )
                ->withResourceName( Str::plural( $type ) )
                ->toArray();
        }

        return $user;
    }

    /**
     * Get the token array structure.
     *
     * @param string  $type
     * @param array   $token
     * @param Request $request
     *
     * @return JsonResponse
     */
    protected function respondWithToken( Admin|Customer|Agent $user, string $type, array $token, Request $request ): JsonResponse
    {
        if ( $request->has( 'device_token' ) ) {
            $user->update( [ 'device_token' => $request->get( 'device_token' ) ] );
        }

        $user = $this->getTransformedUserType( $user, $type );

        return response()->json( [
            'auth' => [
                'token' => $token,
                $type   => $user,
            ],
        ] );
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $guard = request( 'type' );
        $me    = fractal( auth()->guard( $guard )->user(),
            $guard == 'customer' ? new CustomerTransformer : new AdminTransformer )
            ->withResourceName( Str::plural( $guard ) )
            ->toArray();
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
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     * @throws ValidatorException
     */
    public function register( Request $request ): JsonResponse
    {
        $userType = Str::plural( $request->get( 'type', 'customer' ) );
        $table    = ( $userType == "collectors" ) ? "admins" : ( ( $userType == "super_admins" ) ? "admins" : $userType );

        $valid_fields = $this->validate( $request, [
            'name'         => 'required',
            'type'         => 'required|in:customer,collector,admin,super_admin,agent',
            'phone_number' => 'required|unique:' . $table . ',phone_number',
            'company_id'   => 'exists:companies,id',
        ], [], [
            'type' => 'validation',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ] );

        $client = Client::whereProvider( $userType )
            ->where( [ 'password_client' => true ] )
            ->firstOrFail();
        $user   = $this->createUserWithType( $valid_fields, $request->get( 'type' ) );

        $credentials = [
            'username'   => $request->get( 'phone_number' ),
            'password'   => $request->get( 'phone_number' ),
            'company_id' => $request->get( 'company_id' ),
        ];

        $login_request = Request::create( '/oauth/token', 'POST', [
            ...$credentials,
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'grant_type'    => 'password',
        ] );
        $response      = app()->handle( $login_request );

        if ( $response->getStatusCode() != Response::HTTP_OK ) {
            $jsonDecode = json_decode( $response->getContent(), true );
            return response()->json( [
                'code'    => $response->getStatusCode(),
                'type'    => 'Authentication',
                'message' => $jsonDecode['error_description'],
            ], $response->getStatusCode() );
        }

        $token = json_decode( $response->getContent(), true );

        if ( $user instanceof Customer ) {
            event( new NewUserRegistered( $user ) );
        }

        return $this->respondWithToken( $user, $request->get( 'type' ), $token, $request );
    }

    /**
     * @param array  $data
     * @param string $type
     *
     * @return Customer|Admin|Agent
     * @throws ValidatorException
     */
    private function createUserWithType( array $data, string $type ): Agent|Admin|Customer
    {
        if ( $type == 'customer' ) {
            return $this->customerRepository->create(
                array_merge(
                    $data,
                    [
                        'password' => Hash::make( $data['phone_number'] ),
                        'snoocode' => Arr::get( $data, 'code' ) ]
                )
            );
        }

        if ( $type == 'collector' ) {
            return $this->agentRepository->create(
                array_merge(
                    $data,
                    [ 'password' => Hash::make( $data['phone_number'] ), ]
                )
            );
        }

        return $this->adminRepository->create(
            array_merge(
                $data,
                [ 'password' => Hash::make( $data['phone_number'] ) ]
            )
        );
    }
}
