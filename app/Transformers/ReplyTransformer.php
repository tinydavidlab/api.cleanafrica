<?php

namespace App\Transformers;

use App\Models\Reply;
use App\Models\Ticket;
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
        $replyable = $reply->replyable;
        $reflect   = new ReflectionClass( $replyable );
        $replier   = [
            'name' => $replyable->name,
            'type' => strtolower( $reflect->getShortName() ),
        ];

        return [
            'id' => $reply->getAttribute( 'id' ),
            'content' => $reply->getAttribute( 'content' ),
            'photo' => $reply->getAttribute( 'photo' ),
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
}
