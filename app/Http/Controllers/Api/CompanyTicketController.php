<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Transformers\TicketTransformer;
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

        $tickets = $company->tickets;

        $tickets = fractal( $tickets, new TicketTransformer() )
        ->withResourceName('tickets')
        ->toArray();

        return response()->json( ["tickets" => $tickets], Response::HTTP_OK );
    }
}
