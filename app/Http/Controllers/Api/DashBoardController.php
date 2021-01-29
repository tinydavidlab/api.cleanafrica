<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AgentRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\TripRepository;
use App\Repositories\TruckRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashBoardController extends Controller
{
    //

    private $tripRepository;
    private $customerRepository;
    private $agentRepository;
    private $feedBackRepository;
    /**
     * @var TruckRepository
     */
    private $truckRepository;


    public function __construct(TripRepository $tripRepository, CustomerRepository $customerRepository,
                                AgentRepository  $agentRepository, FeedbackRepository $feedBackRepository, TruckRepository $truckRepository)
    {
        $this->tripRepository = $tripRepository;
        $this->customerRepository = $customerRepository;
        $this->agentRepository = $agentRepository;
        $this->feedBackRepository = $feedBackRepository;
        $this->truckRepository = $truckRepository;
    }

    public function index(int $id): JsonResponse
    {
        $today = Carbon::now()->format('Y-m-d');
        $completedTrips = $this->tripRepository->countTripsPerStatusPerDate($id, 'completed', $today );
        $pendingTrips = $this->tripRepository->countTripsPerStatusPerDate($id, 'pending', $today );
        $canceledTrips = $this->tripRepository->countTripsPerStatusPerDate($id, 'canceled', $today );

        $companyTrucks = $this->truckRepository->countCompanyTrucks($id);

        $companyCollectors = $this->agentRepository->countCollectorsForCompany($id);

        return response()->json( [
            'trips' => [
                'completed_trips' => $completedTrips,
                'pending_trips' => $pendingTrips,
                'canceled_trips' => $canceledTrips,],
            'trucks' => ['no_of_trucks' => $companyTrucks],
            'collectors' => ['no_of_collectors' => $companyCollectors]
        ], Response::HTTP_OK );
    }
}