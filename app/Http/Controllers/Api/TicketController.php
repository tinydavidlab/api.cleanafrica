<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\TicketRepository;
use App\Transformers\TicketTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;

class TicketController extends Controller
{
    /**
     * @var TicketRepository
     */
    private $repository;

    /**
     * TicketController constructor.
     * @param TicketRepository $repository
     */
    public function __construct( TicketRepository $repository )
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
        $tickets = $this->repository->orderBy( 'created_at' )->get();
        $tickets = fractal( $tickets, new TicketTransformer )
            ->withResourceName( 'tickets' )
            ->toArray();

        return response()->json( [ 'tickets' => $tickets ], Response::HTTP_OK );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidatorException
     * @throws ValidationException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'category_id' => 'required|exists:categories,id',
            'customer_id' => 'required|exists:customers,id',
            'agent_id' => 'nullable|exists:agents,id',
            'subject' => 'required',
            'content' => 'required',
            'photo' => 'required|image',
            'priority' => 'required',
        ] );

        $ticket = $this->repository->create( $request->except( 'photo' ) );
        if ( $request->hasFile( 'photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'support_tickets' ) );
            $this->repository->update( [ 'photo' => $filename ], $ticket->id );
        }

        $ticket = fractal( $ticket->fresh(), new TicketTransformer )
            ->withResourceName( 'tickets' )
            ->toArray();

        return response()->json( [ 'tickets' => $ticket ], Response::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        $ticket = $this->repository->find( $id );
        $ticket = fractal( $ticket, new TicketTransformer )
            ->withResourceName( 'tickets' )
            ->toArray();

        return response()->json( [ 'ticket' => $ticket ], Response::HTTP_OK );
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
        $ticket = $this->repository->update( $request->except( 'photo' ), $id );
        if ( $request->hasFile( 'photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'support_tickets' ) );
            $this->repository->update( [ 'photo' => $filename ], $ticket->id );
        }
        $ticket = fractal( $ticket, new TicketTransformer )
            ->withResourceName( 'tickets' )
            ->toArray();

        return response()->json( [ 'tickets' => $ticket ], Response::HTTP_OK );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy( int $id ): JsonResponse
    {
        $this->repository->delete( $id );

        return response()->json( [], Response::HTTP_OK );
    }
}
