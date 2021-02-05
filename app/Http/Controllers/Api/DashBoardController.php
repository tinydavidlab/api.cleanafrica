<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AgentRepository;
use App\Repositories\CompanyRepository;
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
    /**
     * @var CompanyRepository
     */
    private $companyRepository;


    public function __construct(TripRepository $tripRepository, CustomerRepository $customerRepository,
                                AgentRepository  $agentRepository, FeedbackRepository $feedBackRepository,
                                TruckRepository $truckRepository, CompanyRepository  $companyRepository)
    {
        $this->tripRepository = $tripRepository;
        $this->customerRepository = $customerRepository;
        $this->agentRepository = $agentRepository;
        $this->feedBackRepository = $feedBackRepository;
        $this->truckRepository = $truckRepository;
        $this->companyRepository = $companyRepository;
    }

    public function index(int $id): JsonResponse
    {
        $today = Carbon::now()->format('Y-m-d');
        $completedTrips = $this->tripRepository->countTripsPerStatusPerDate($id, 'completed', $today );
        $pendingTrips = $this->tripRepository->countTripsPerStatusPerDate($id, 'pending', $today );
        $canceledTrips = $this->tripRepository->countTripsPerStatusPerDate($id, 'canceled', $today );

        $companyTrucks = $this->truckRepository->countCompanyTrucks($id);
        $companyCollectors = $this->agentRepository->countCollectorsForCompany($id);
        $companyCustomers = $this->customerRepository->countCustomersForCompany($id);
        $companyTotalTripsForToday = $this->tripRepository->countTripsForToday($id, $today);

        $companyFeedbacksForToday = $this->feedBackRepository->countTotalFeedbacksForToday($id, $today);

        return response()->json( [
            'trips' => [
                'completed_trips' => $completedTrips,
                'pending_trips' => $pendingTrips,
                'canceled_trips' => $canceledTrips,
                'total_trips' => $companyTotalTripsForToday,
                ],
            'trucks' => ['no_of_trucks' => $companyTrucks],
            'collectors' => ['no_of_collectors' => $companyCollectors],
            'customers' => ['no_of_customers' => $companyCustomers],
            'feedback' => ['no_of_feedbacks' => $companyFeedbacksForToday],
        ], Response::HTTP_OK );
    }

    public function getStatistics()
    {
        $today = Carbon::now()->format('Y-m-d');
        $completedTrips = $this->tripRepository->countAllTripsByStatus('completed');
        $pendingTrips = $this->tripRepository->countAllTripsByStatus( 'pending' );
        $canceledTrips = $this->tripRepository->countAllTripsByStatus( 'canceled' );
        $totalTrips = $this->tripRepository->countAllTrips();

        $trucks = $this->truckRepository->countAllTrucks();
        $collectors = $this->agentRepository->countAllCollectors();
        $customers = $this->customerRepository->countAllCustomers();
        $feedback = $this->feedBackRepository->countAllFeedBacks();
        $companies = $this->companyRepository->countAllCompanies();
        return response()->json( [
            'trips' => [
                'completed_trips' => $completedTrips,
                'pending_trips' => $pendingTrips,
                'canceled_trips' => $canceledTrips,
                'total_trips' => $totalTrips,
            ],
            'trucks' => ['no_of_trucks' => $trucks],
            'collectors' => ['no_of_collectors' => $collectors],
            'customers' => ['no_of_customers' => $customers],
            'feedback' => ['no_of_feedbacks' => $feedback],
            'companies' => ['no_of_companies' => $companies]
        ], Response::HTTP_OK );
    }
}
