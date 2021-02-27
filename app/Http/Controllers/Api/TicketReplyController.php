<?php

namespace App\Http\Controllers\Api;

use App\Events\AdminRepliedTicket;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Repositories\ReplyRepository;
use App\Repositories\TicketRepository;
use App\Transformers\ReplyTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class TicketReplyController extends Controller
{
    /**
     * @var TicketRepository
     */
    private $repository;
    /**
     * @var ReplyRepository
     */
    private $replyRepository;

    /**
     * TicketReplyController constructor.
     *
     * @param TicketRepository $repository
     * @param ReplyRepository $replyRepository
     */
    public function __construct( TicketRepository $repository, ReplyRepository $replyRepository )
    {
        $this->repository      = $repository;
        $this->replyRepository = $replyRepository;
    }

    /**
     * Fetch all replies for a single ticket
     *
     * @param int $id
     * @return JsonResponse
     */
    public function index( int $id ): JsonResponse
    {
        $replies = $this->replyRepository->getForTicket( $id );
        $replies = fractal( $replies, new ReplyTransformer )
            ->withResourceName( 'replies' )
            ->toArray();

        return response()->json( [ 'replies' => $replies ], Response::HTTP_OK );
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidatorException
     * @throws ValidationException
     */
    public function store( int $id, Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'content' => 'required',
            'photo' => 'image'
        ] );

        $admin = auth()->guard( 'admin' )->user();

        $reply = $this->replyRepository->create(
            array_merge(
                $request->except( 'photo' ),
                [ 'ticket_id' => $id, 'replyable_id' => $admin->id, 'replyable_type' => get_class( $admin ) ]
            )
        );

        if ( $request->hasFile( 'photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'replies' ) );
            $this->replyRepository->update( [ 'photo' => $filename ], $reply->id );
        }

        event( new AdminRepliedTicket( $reply ) );

        $reply = fractal( $reply, new ReplyTransformer )
            ->withResourceName( 'replies' )
            ->toArray();

        return response()->json( [ 'reply' => $reply ], Response::HTTP_CREATED );
    }
}
