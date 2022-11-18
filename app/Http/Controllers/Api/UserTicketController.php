<?php

namespace App\Http\Controllers\Api;

use App\Enums\TicketStatus;
use App\Events\CustomerRepliedTicket;
use App\Events\UserNewTicket;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\CategoryRepository;
use App\Repositories\ReplyRepository;
use App\Repositories\TicketRepository;
use App\Transformers\ReplyTransformer;
use App\Transformers\TicketTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class UserTicketController extends Controller
{
    /**
     * @var TicketRepository
     */
    private $repository;

    private $categoryRepository;
    private $replyRepository;

    /**
     * TicketController constructor.
     * @param TicketRepository $repository
     * @param CategoryRepository $categoryRepository
     * @param ReplyRepository $replyRepository
     */
    public function __construct( TicketRepository $repository, CategoryRepository $categoryRepository, ReplyRepository $replyRepository )
    {
        $this->repository         = $repository;
        $this->categoryRepository = $categoryRepository;
        $this->replyRepository    = $replyRepository;
    }

    /**
     * Fetch all authenticated customer tickets.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index( Request $request ): JsonResponse
    {
        if ( !auth()->check() ) {
            $tickets = collect();
        } else {
            if ( $request->has( 'status' ) ) {
                $tickets = auth()->user()->tickets()->whereStatus( $request->get( 'status' ) )->latest()->get();
            } else {
                $tickets = auth()->user()->tickets()->latest()->get();
            }
        }

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
     * @throws ValidationException
     * @throws ValidatorException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'category_id' => 'required|exists:categories,id',
            'subject' => 'required',
            'content' => 'required',
            'photo' => 'image',
        ] );

        $status = TicketStatus::OPEN;
        if ( $request->has( 'category_id' ) ) {
            $category = $this->categoryRepository->find( $request->get( 'category_id' ) );
            if ( $category && $category->name == "Feedback" ) {
                $status = TicketStatus::CLOSED;
            }
        }

        $ticket = $this->repository->create(
            array_merge(
                $request->except( 'photo' ),
                [ 'customer_id' => auth()->id(), 'status' => $status ] )
        );

        if ( $request->hasFile( 'photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'support_tickets' ) );
            $this->repository->update( [ 'photo' => $filename ], $ticket->id );
        }

        event( new UserNewTicket( auth()->user(), $ticket, $status ) );

        $ticket = fractal( $ticket->fresh(), new TicketTransformer )
            ->withResourceName( 'tickets' )
            ->toArray();

        return response()->json( [ 'ticket' => $ticket ], Response::HTTP_CREATED );
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws ValidatorException
     */
    public function reply( int $id, Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'content' => 'required',
            'photo' => 'image'
        ] );

        $customer = auth()->user();

        $reply = $this->replyRepository->create(
            array_merge(
                $request->except( 'photo' ),
                [ 'ticket_id' => $id, 'replyable_type' => get_class( $customer ), 'replyable_id' => $customer->id ]
            )
        );

        if ( $request->hasFile( 'photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'replies' ) );
            $this->replyRepository->update( [ 'photo' => $filename ], $reply->id );
        }

        event(new CustomerRepliedTicket( $reply ));

        $reply = fractal( $reply, new ReplyTransformer )
            ->withResourceName( 'replies' )
            ->toArray();

        return response()->json( [ 'reply' => $reply ], Response::HTTP_OK );
    }
}
