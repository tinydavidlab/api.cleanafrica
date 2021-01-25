<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AgentRepository;
use App\Transformers\AgentTransformer;
use App\Transformers\CustomerTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class AgentController extends Controller
{
    /**
     * @var AgentRepository
     */
    private $repository;

    /**
     * CompanyAgentController constructor.
     * @param AgentRepository $repository
     */
    public function __construct( AgentRepository $repository )
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
        $agents = $this->repository->all();

        $agents = fractal( $agents, new AgentTransformer() )
            ->withResourceName( 'agents' )
            ->toArray();

        return response()->json( [ 'agents' => $agents ], Response::HTTP_OK );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws ValidatorException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'name' => 'required',
            'phone_number' => 'required|unique:agents',
            'type' => 'required',
        ] );

        $agent = $this->repository->create( [
           'password' => Hash::make( $request[ 'phone_number' ] ),
            'name' => $request['name'],
            'phone_number' => $request['phone_number'],
            'type' => $request['type'],
            'company_id' => $request['company_id'],

        ] );
        $agent = fractal( $agent, new AgentTransformer() )
            ->withResourceName( 'agents' )
            ->toArray();

        return response()->json( [ 'agent' => $agent ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        try {
            $agent = $this->repository->find( $id );

            $agent = fractal( $agent, new AgentTransformer )
                ->withResourceName( 'agents' )
                ->toArray();

            return response()->json( [ 'agent' => $agent ], Response::HTTP_OK );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json( [ 'message' => 'No agent was found with id: ' . $id ], Response::HTTP_NOT_FOUND );
        }
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
            $agent = $this->repository->update( $request->except( 'company_id' ), $id );

            $agent = fractal( $agent->fresh(), new AgentTransformer )
                ->withResourceName( 'agents' )
                ->toArray();

            return response()->json( [ 'agent' => $agent ], Response::HTTP_OK );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json(
                [ 'message' => 'No agent was found with: ' . $request->get( 'company_id' ) ], Response::HTTP_NOT_FOUND );
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

            return response()->json( [], Response::HTTP_NO_CONTENT );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json(
                [ 'message' => 'No agent was found with: ' . $id ], Response::HTTP_NOT_FOUND );
        }
    }
}
