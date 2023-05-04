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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

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
     * @var AgentRepository
     */
    private $agentRepository;


    /**
     * AuthController constructor.
     * @param CustomerRepository $customerRepository
     * @param AdminRepository $adminRepository
     * @param AgentRepository $agentRepository
     */
    public function __construct(CustomerRepository $customerRepository, AdminRepository $adminRepository, AgentRepository $agentRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->adminRepository = $adminRepository;
        $this->agentRepository = $agentRepository;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'username' => 'required',
            'grant_type' => 'in:password',
            'client_id' => 'required',
            'client_secret' => 'required',
            'type' => 'in:customer,collector,admin,super_admin',
        ]);

        $type = $request->get('type', 'admin');

        $credentials = [
            'grant_type' => $request->get('grant_type', 'password'),
            'username' => $request->get('username'),
            'password' => $request->get('username'),
            'client_id' => $request->get('client_id'),
            'client_secret' => $request->get('client_secret'),
            'scope' => $request->get('scope', ''),
        ];

        return $this->handleAuthentication($credentials, $request, $type);
    }

    protected function getUserForGrantType(string $type, array $auth_token)
    {
        $access_token = Arr::get($auth_token, 'access_token');

        if (!$jwt = $access_token) return null;
        [$headb64, $bodyb64, $cryptob64] = explode('.', $jwt);
        $body = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
        if (!$id = data_get($body, 'sub')) return null;

        if ($type == 'customer') {
            return Customer::find($id);
        } elseif ($type == 'collector') {
            return Agent::find($id);
        }

        return Admin::find($id);
    }

    private function getTransformedUserType($user, $type = 'admin'): array
    {
        if ($type == 'collector') {
            $user = fractal($user, new AgentTransformer())
                ->withResourceName(Str::plural($type))
                ->toArray();
        } else if ($type == 'customer') {
            $user = fractal($user, new CustomerTransformer())
                ->withResourceName(Str::plural($type))
                ->toArray();
        } else {
            $user = fractal($user, new AdminTransformer())
                ->withResourceName(Str::plural($type))
                ->toArray();
        }

        return $user;
    }

    /**
     * Get the token array structure.
     *
     * @param string $type
     * @param string $token
     * @param Request $request
     * @return JsonResponse
     */
    protected function respondWithToken(string $type, string $token, Request $request): JsonResponse
    {
        $payload = JWTAuth::setToken($token)->getPayload();

        $token = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $payload->get('exp'),
        ];

        $user = auth()->guard($type)->user();
        if ($request->has('device_token')) {
            $user->update(['device_token' => $request->get('device_token')]);
        }

        $user = $this->getTransformedUserType($user, $type);

        return response()->json([
            'auth' => [
                'token' => $token,
                $type => $user
            ]
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $guard = request('type');
        $me = fractal(auth()->guard($guard)->user(), $guard == 'customer' ? new CustomerTransformer : new AdminTransformer)->withResourceName(Str::plural($guard))->toArray();
        return response()->json(['me' => $me]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws ValidatorException
     */
    public function register(Request $request): JsonResponse
    {
        $table = Str::plural($request->get('type', 'customer'));
        $table = ($table == "collectors") ? "admins" : (($table == "super_admins") ? "admins" : $table);

        $this->validate($request, [
            'name' => 'required',
            'type' => 'required|in:customer,collector,admin,super_admin',
            'phone_number' => 'required|unique:' . $table . ',phone_number',
            'company_id' => 'exists:companies,id',
            'grant_type' => 'in:password',
            'client_id' => 'required',
            'client_secret' => 'required',
            'scope' => 'nullable',
        ], [], [
            'type' => 'validation',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ]);

        $type = $request->get('type');

        $user = $this->createUserWithType($request->all(), $type);

        $credentials = [
            'grant_type' => $request->get('grant_type', 'password'),
            'username' => $request->get('phone_number'),
            'password' => $request->get('phone_number'),
            'client_id' => $request->get('client_id', env('CLIENT_ID')),
            'client_secret' => $request->get('client_secret', env('CLIENT_SECRET')),
            'scope' => $request->get('scope', ''),
        ];

        event(new NewUserRegistered($user));

        return $this->handleAuthentication($credentials, $request, $type);
    }

    /**
     * @param array $data
     * @param string $type
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    private function createUserWithType(array $data, string $type)
    {

        if ($type == 'customer') {
            return $this->customerRepository->create(
                array_merge(
                    $data,
                    ['password' => Hash::make($data['phone_number']),
                        'snoocode' => Arr::get($data, 'code')]
                )
            );
        }

        if ($type == 'collector') {
            return $this->agentRepository->create(
                array_merge(
                    $data,
                    ['password' => Hash::make($data['phone_number']),
                        'device_token' => $data['device_token']
                    ]
                )
            );
        }

        return $this->adminRepository->create(
            array_merge(
                $data,
                ['password' => Hash::make($data['phone_number'])]
            )
        );
    }

    /**
     * @param array $credentials
     * @param Request $request
     * @param $type
     * @return JsonResponse
     */
    protected function handleAuthentication(array $credentials, Request $requestFromApp, $type): JsonResponse
    {
        $request = Request::create('/oauth/token', 'POST', $credentials);
        $response = app()->handle($request);
        $auth_token = json_decode($response->getContent(), true);
        if ($response->getStatusCode() != Response::HTTP_OK) {
            return response()->json([
                'message' => Arr::get($auth_token, 'message')
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getUserForGrantType($type, $auth_token);

        $this->agentRepository->update(['device_token' => $requestFromApp->get('device_token')], $user->id);

        return response()->json([
            'auth' => [
                'token' => $auth_token,
                $type => $this->getTransformedUserType($user, $type)
            ]
        ], Response::HTTP_OK);
    }
}
