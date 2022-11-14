<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AgentRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\TicketRepository;
use App\Repositories\TripRepository;
use App\Repositories\TruckRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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
    /**
     * @var TicketRepository
     */
    private $ticketRepository;


    public function __construct( TripRepository $tripRepository, CustomerRepository $customerRepository,
        AgentRepository $agentRepository, FeedbackRepository $feedBackRepository,
        TruckRepository $truckRepository, CompanyRepository $companyRepository,
        TicketRepository $ticketRepository )
    {
        $this->tripRepository     = $tripRepository;
        $this->customerRepository = $customerRepository;
        $this->agentRepository    = $agentRepository;
        $this->feedBackRepository = $feedBackRepository;
        $this->truckRepository    = $truckRepository;
        $this->companyRepository  = $companyRepository;
        $this->ticketRepository   = $ticketRepository;
    }

    public function index( int $id ): JsonResponse
    {
        $today          = Carbon::now()->format( 'Y-m-d' );
        $completedTrips = $this->tripRepository->countCompanyTripsPerStatusPerDate( $id, 'completed', $today );
        $pendingTrips   = $this->tripRepository->countCompanyTripsPerStatusPerDate( $id, 'pending', $today );
        $canceledTrips  = $this->tripRepository->countCompanyTripsPerStatusPerDate( $id, 'canceled', $today );

        $companyTrucks             = $this->truckRepository->countCompanyTrucks( $id );
        $companyCollectors         = $this->agentRepository->countCollectorsForCompany( $id );
        $companyCustomers          = $this->customerRepository->countCustomersForCompany( $id );
        $companyTotalTripsForToday = $this->tripRepository->countCompanyTripsForToday( $id, $today );

        $company = $this->companyRepository->find($id);

        $noOfTickets = $company->tickets()->count();
        $noOfOpenTickets = $company->tickets()->where( 'status', 'OPEN' )->count();
        $noOfClosedTickets = $company->tickets()->where( 'status', 'CLOSED' )->count();


        return response()->json( [
            'trips' => [
                'completed_trips' => $completedTrips,
                'pending_trips' => $pendingTrips,
                'canceled_trips' => $canceledTrips,
                'total_trips' => $companyTotalTripsForToday,
            ],
            'trucks' => [ 'no_of_trucks' => $companyTrucks ],
            'collectors' => [ 'no_of_collectors' => $companyCollectors ],
            'customers' => [ 'no_of_customers' => $companyCustomers ],
            'tickets' => [
                'no_of_tickets' => $noOfTickets,
                'no_of_open_tickets' => $noOfOpenTickets,
                'no_of_closed_tickets' => $noOfClosedTickets,
            ],
        ], Response::HTTP_OK );
    }

    public function getStatistics()
    {
        $today          = Carbon::now()->format( 'Y-m-d' );
        $completedTrips = $this->tripRepository->countAllTripsByStatus( 'completed' );
        $pendingTrips   = $this->tripRepository->countAllTripsByStatus( 'pending' );
        $canceledTrips  = $this->tripRepository->countAllTripsByStatus( 'canceled' );
        $totalTrips     = $this->tripRepository->countAllTrips();

        $trucks     = $this->truckRepository->countAllTrucks();
        $collectors = $this->agentRepository->countAllCollectors();
        $customers  = $this->customerRepository->countAllCustomers();
        $feedback   = $this->feedBackRepository->countAllFeedBacks();
        $companies  = $this->companyRepository->countAllCompanies();

        $noOfTickets = $this->ticketRepository->getCountOfTickets();
        $noOfOpenTickets = $this->ticketRepository->getCountOfTicketsPerStatus( 'OPEN' );
        $noOfClosedTickets = $this->ticketRepository->getCountOfTicketsPerStatus( 'CLOSED' );

        return response()->json( [
            'trips' => [
                'completed_trips' => $completedTrips,
                'pending_trips' => $pendingTrips,
                'canceled_trips' => $canceledTrips,
                'total_trips' => $totalTrips,
            ],
            'trucks' => [ 'no_of_trucks' => $trucks ],
            'collectors' => [ 'no_of_collectors' => $collectors ],
            'customers' => [ 'no_of_customers' => $customers ],
            'tickets' => [
                'no_of_tickets' => $noOfTickets,
                'no_of_open_tickets' => $noOfOpenTickets,
                'no_of_closed_tickets' => $noOfClosedTickets,],
            'companies' => [ 'no_of_companies' => $companies ]
        ], Response::HTTP_OK );
    }

    public function getStatisticsPerCriteria( $criteria )
    {
        $today          = Carbon::now()->format( 'Y-m-d' );
        $yesterday      = Carbon::yesterday()->format( 'Y-m-d' );
        $totalTrips     = 0;
        $completedTrips = 0;
        $pendingTrips   = 0;
        $canceledTrips  = 0;

        if ( $criteria == 'today' ) {
            $totalTrips     = $this->tripRepository->countAllTripsForToday( $today );
            $completedTrips = $this->tripRepository->countAllTripsByStatusAndDate( 'completed', $today );
            $pendingTrips   = $this->tripRepository->countAllTripsByStatusAndDate( 'pending', $today );
            $canceledTrips  = $this->tripRepository->countAllTripsByStatusAndDate( 'canceled', $today );
        }

        if ( $criteria == 'yesterday' ) {
            $totalTrips     = $this->tripRepository->countAllTripsForToday( $yesterday );
            $completedTrips = $this->tripRepository->countAllTripsByStatusAndDate( 'completed', $yesterday );
            $pendingTrips   = $this->tripRepository->countAllTripsByStatusAndDate( 'pending', $yesterday );
            $canceledTrips  = $this->tripRepository->countAllTripsByStatusAndDate( 'canceled', $yesterday );
        }

        if ( $criteria == 'week' ) {
            $totalTrips     = $this->tripRepository->countTotalTripsForTheWeek();
            $pendingTrips   = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'pending' ] );
            } )->countTotalTripsForTheWeek();
            $completedTrips = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'completed' ] );
            } )->countTotalTripsForTheWeek();
            $canceledTrips  = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'canceled' ] );
            } )->countTotalTripsForTheWeek();
        }

        if ( $criteria == 'month' ) {
            $totalTrips     = $this->tripRepository->countTotalTripsForTheMonth();
            $pendingTrips   = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'pending' ] );
            } )->countTotalTripsForTheMonth();
            $completedTrips = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'completed' ] );
            } )->countTotalTripsForTheMonth();
            $canceledTrips  = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'canceled' ] );
            } )->countTotalTripsForTheMonth();
        }

        if ( $criteria == 'year' ) {
            $totalTrips     = $this->tripRepository->countTotalTripsForThisYear();
            $pendingTrips   = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'pending' ] );
            } )->countTotalTripsForThisYear();
            $completedTrips = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'completed' ] );
            } )->countTotalTripsForThisYear();
            $canceledTrips  = $this->tripRepository->scopeQuery( function ( $query ) {
                return $query->where( [ 'delivery_status' => 'canceled' ] );
            } )->countTotalTripsForThisYear();
        }

        return response()->json( [
            'trips' => [
                'completed_trips' => $completedTrips,
                'pending_trips' => $pendingTrips,
                'canceled_trips' => $canceledTrips,
                'total_trips' => $totalTrips,
            ],
        ], Response::HTTP_OK );
    }

    public function getStatisticPerCriteriaPerCompany( int $id, $criteria )
    {
        $company = $this->companyRepository->find($id);
        $today          = Carbon::now()->format( 'Y-m-d' );
        $yesterday      = Carbon::yesterday()->format( 'Y-m-d' );
        $totalTrips     = 0;
        $completedTrips = 0;
        $pendingTrips   = 0;
        $canceledTrips  = 0;
        $noOfTickets  = 0;
        $noOfOpenTickets  = 0;
        $noOfClosedTickets  = 0;

        if ( $criteria == 'today' ) {
            $totalTrips     = $this->tripRepository->
            scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countAllTripsForToday( $today );
            $completedTrips = $this->tripRepository->
            scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countAllTripsByStatusAndDate( 'completed', $today );
            $pendingTrips   = $this->tripRepository->
            scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countAllTripsByStatusAndDate( 'pending', $today );
            $canceledTrips  = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countAllTripsByStatusAndDate( 'canceled', $today );

            $noOfTickets = $company->tickets()->whereDate('tickets.created_at', Carbon::today())->count();
            $noOfOpenTickets = $company->tickets()->whereDate('tickets.created_at', Carbon::today())
                ->where( 'tickets.status', 'OPEN' )->count();
            $noOfClosedTickets = $company->tickets()->whereDate('tickets.created_at', Carbon::today())
                ->where( 'tickets.status', 'CLOSED' )->count();
        }

        if ( $criteria == 'yesterday' ) {
            $totalTrips     = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countAllTripsForToday( $yesterday );
            $completedTrips = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countAllTripsByStatusAndDate( 'completed', $yesterday );
            $pendingTrips   = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countAllTripsByStatusAndDate( 'pending', $yesterday );
            $canceledTrips  = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countAllTripsByStatusAndDate( 'canceled', $yesterday );

            $noOfTickets = $company->tickets()->whereDate('tickets.created_at', Carbon::yesterday())->count();
            $noOfOpenTickets = $company->tickets()->whereDate('tickets.created_at', Carbon::yesterday())
                ->where( 'tickets.status', 'OPEN' )->count();
            $noOfClosedTickets = $company->tickets()->whereDate('tickets.created_at', Carbon::yesterday())
                ->where( 'tickets.status', 'CLOSED' )->count();
        }

        if ( $criteria == 'week' ) {
            $totalTrips     = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [ 'company_id' => $id ] );
            } )->countTotalTripsForTheWeek();
            $pendingTrips   = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'pending',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForTheWeek();
            $completedTrips = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'completed',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForTheWeek();
            $canceledTrips  = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'canceled',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForTheWeek();

            $noOfTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $noOfOpenTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where( 'tickets.status', 'OPEN' )->count();
            $noOfClosedTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where( 'tickets.status', 'CLOSED' )->count();
        }

        if ( $criteria == 'month' ) {
            $totalTrips     = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'company_id' => $id
                ] );
            } )->countTotalTripsForTheMonth();
            $pendingTrips   = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'pending',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForTheMonth();
            $completedTrips = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'completed',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForTheMonth();
            $canceledTrips  = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'canceled',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForTheMonth();

            $noOfTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
            $noOfOpenTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->where( 'tickets.status', 'OPEN' )->count();
            $noOfClosedTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->where( 'tickets.status', 'CLOSED' )->count();
        }

        if ( $criteria == 'year' ) {
            $totalTrips     = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'company_id' => $id
                ] );
            } )->countTotalTripsForThisYear();
            $pendingTrips   = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'pending',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForThisYear();
            $completedTrips = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'completed',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForThisYear();
            $canceledTrips  = $this->tripRepository->scopeQuery( function ( $query ) use ( $id ) {
                return $query->where( [
                    'delivery_status' => 'canceled',
                    'company_id' => $id
                ] );
            } )->countTotalTripsForThisYear();

            $noOfTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->count();
            $noOfOpenTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
                ->where( 'tickets.status', 'OPEN' )->count();
            $noOfClosedTickets = $company->tickets()->whereBetween('tickets.created_at',
                [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
                ->where( 'tickets.status', 'CLOSED' )->count();
        }

        return response()->json( [
            'trips' => [
                'completed_trips' => $completedTrips,
                'pending_trips' => $pendingTrips,
                'canceled_trips' => $canceledTrips,
                'total_trips' => $totalTrips,
            ],
            'tickets' => [
                'no_of_tickets' => $noOfTickets,
                'no_of_open_tickets' => $noOfOpenTickets,
                'no_of_closed_tickets' => $noOfClosedTickets,
               // 'no_of_closed_tickets' => Carbon::today()->toDateString(),
            ],
        ], Response::HTTP_OK );
    }
}
