<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Transformers\TicketTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyTicketController extends Controller
{

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepository) {

        $this->companyRepository = $companyRepository;
    }

    public function getTicketsForCompany( int $id ): \Illuminate\Http\JsonResponse
    {
        $company = $this->companyRepository->find($id);

        $tickets = $company->tickets()->orderBy('created_at','desc')->get();

        $tickets = fractal( $tickets, new TicketTransformer() )
        ->withResourceName('tickets')
        ->toArray();

        return response()->json( ["tickets" => $tickets], Response::HTTP_OK );
    }

    public function getCompanyTicketsPerDate( int $id, $date )
    {
        $company = $this->companyRepository->find($id);

        $tickets = $company->tickets()->whereDate('tickets.created_at',Carbon::parse($date))
            ->orderBy('created_at','desc')
            ->get();

        $tickets = fractal( $tickets, new TicketTransformer() )
            ->withResourceName('tickets')
            ->toArray();

        return response()->json( ["tickets" => $tickets], Response::HTTP_OK );
    }
}
