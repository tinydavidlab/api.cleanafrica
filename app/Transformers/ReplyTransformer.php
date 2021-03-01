<?php

namespace App\Transformers;

use App\Models\Company;
use App\Models\Reply;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use ReflectionClass;
use ReflectionException;

class ReplyTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'replier', 'ticket'
    ];

    /**
     * A Fractal transformer.
     *
     * @param Reply $reply
     * @return array
     * @throws ReflectionException
     */
    public function transform( Reply $reply ): array
    {
        $replyable    = $reply->replyable;
        $reflect      = new ReflectionClass( $replyable );
        $replier_type = strtolower( $reflect->getShortName() );
        $replier      = [
            'name' => $replyable->name,
            'type' => $replier_type,
            'is_admin' => $replier_type == 'admin' ? true : false
        ];

        if ( $replier_type == 'admin' ) {
            $replier[ 'logo' ] = $this->getImageUrl( $replyable->company );
        }

        $address = $reply->getAttribute( 'address' );
        if ( is_string( $reply->getAttribute( 'address' ) ) ) {
            $address = json_decode( $address, true );
        }

        return [
            'id' => $reply->getAttribute( 'id' ),
            'content' => $reply->getAttribute( 'content' ),
            'photo' => $this->getReplyImageUrl($reply),
            //'photo' => $reply->getAttribute('photo'),
            'snoocode' => Arr::get( $address, 'code' ),
            'country' => Arr::get( $address, 'country' ),
            'subdivision' => Arr::get( $address, 'subdivision' ),
            'division' => Arr::get( $address, 'division' ),
            'latitude' => Arr::get( $address, 'latitude' ),
            'longitude' => Arr::get( $address, 'longitude' ),
            'signature' => Arr::get( $address, 'stamp_string' ),
            'sent_at' => Carbon::parse($reply->getAttribute( 'created_at' ))->format( 'l, d F Y H:i:s' ),
            'created_at' => $reply->getAttribute( 'created_at' )->diffForHumans(),
            'replier' => $replier
        ];
    }



    public function includeCustomer( Ticket $ticket ): ?Item
    {
        $customer = $ticket->customer;
        if ( !$customer ) return null;

        return $this->item( $customer, new CustomerTransformer, 'customers' );
    }

    public function includeAgent( Reply $reply ): ?Item
    {
        $agent = $reply->agent;
        if ( !$agent ) return null;

        return $this->item( $agent, new AgentTransformer, 'agents' );
    }

    public function includeTicket( Reply $reply ): Item
    {
        return $this->item( $reply->ticket, new TicketTransformer, 'tickets' );
    }

    /**
     * @param Reply $reply
     * @return Item
     * @throws ReflectionException
     */
    public function includeReplier( Reply $reply ): Item
    {
        $replyable = $reply->replyable;
        $reflect   = new ReflectionClass( $replyable );
        if ( $reflect->getShortName() == "Customer" ) {
            return $this->item( $replyable, new CustomerTransformer, 'customers' );
        }
        return $this->item( $replyable, new AdminTransformer, 'admins' );
    }

    public function getImageUrl( Company $company ): ?string
    {
        if ( $company->getAttribute( 'logo' ) == null ) {
            return null;
        }
        return Storage::disk( 's3' )->url( 'companies/' . $company->getAttribute( 'logo' ) );
    }

    public function getReplyImageUrl(Reply $reply)
    {
        if ($reply->getAttribute('photo') == null) {
            return null;
        }
        return Storage::disk('s3')->url('replies/'. $reply->getAttribute('photo'));
    }
}
