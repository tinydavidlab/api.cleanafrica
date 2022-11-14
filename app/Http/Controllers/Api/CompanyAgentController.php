<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AgentRepository;
use App\Transformers\AgentTransformer;
use App\Transformers\TripTransformer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class CompanyAgentController extends Controller
{
    /**
     * @var AgentRepository
     */
    private $repository;

    /**
     * CompanyAgentController constructor.
     * @param AgentRepository $repository
     */
    public function __construct(AgentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function index(int $id): JsonResponse
    {
        $agents = $this->repository->getForCompany($id);

        $agents = fractal($agents, new AgentTransformer())
            ->withResourceName('agents')
            ->toArray();

        return response()->json(['agents' => $agents], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'phone_number' => 'required|unique:agents',
            'type' => 'required',
        ]);

        try {
            $agent = $this->repository->create(array_merge($request->all(), ['company_id' => $id]));
            $agent = fractal($agent, new AgentTransformer())
                ->withResourceName('agents')
                ->toArray();

            return response()->json(['agent' => $agent], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json(['message' => 'Please check the company if it exists.'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param int $agent_id
     * @return JsonResponse
     */
    public function show(int $id, int $agent_id): JsonResponse
    {
        try {
            $agent = $this->repository->findForCompany($id, $agent_id);

            $agent = fractal($agent, new AgentTransformer)
                ->withResourceName('agents')
                ->toArray();

            return response()->json(['agent' => $agent], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'No agent was found with id: ' . $agent_id], Response::HTTP_NOT_FOUND);
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
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $agent = $this->repository->update($request->all(), $id);

            $agent = fractal($agent->fresh(), new AgentTransformer())
                ->withResourceName('agents')
                ->toArray();

            return response()->json(['agent' => $agent], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(
                ['message' => 'No agent was found with: ' . $id], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $agent_id
     * @return JsonResponse
     */
    public function destroy(int $id, int $agent_id): JsonResponse
    {
        try {
            $this->repository->delete($agent_id);

            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return response()->json(
                ['message' => 'No agent was found with: ' . $id], Response::HTTP_NOT_FOUND);
        }
    }

    public function getCompanyAgentByType(int $id, $type)
    {
        $agents = $this->repository->
        scopeQuery(function ($query) use ($id, $type) {
            return $query->where([
                'type' => $type,
                'company_id' => $id
            ]);
        })->all();

        $agents = fractal($agents, new AgentTransformer())
            ->withResourceName('agents')
            ->toArray();

        return response()->json(['agents' => $agents], Response::HTTP_OK);
    }

    public function getTripsForSpecificTruckAndAgent(int $id , $date, $status): JsonResponse
    {
        $collector = $this->repository->find($id);
        $trips = collect();
        foreach ($collector->trucks as $truck) {
            $trips[] = $truck->trips;
        }

        $trips = $trips->flatten()
            ->where('collector_date',$date)
        ->where('delivery_status', $status);

        $trips = fractal($trips, new TripTransformer)
            ->withResourceName('trips')
            ->toArray();

        return response()->json(['trips' => $trips], Response::HTTP_OK);
    }
}
