<?php

namespace App\Transformers;

use App\Models\Reply;
use App\Models\Ticket;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

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
        'customer', 'agent'
    ];

    /**
     * A Fractal transformer.
     *
     * @param Reply $reply
     * @return array
     */
    public function transform( Reply $reply ): array
    {
        return [
            'id' => $reply->getAttribute( 'id' ),
            'content' => $reply->getAttribute( 'content' ),
            'photo' => $reply->getAttribute( 'photo' ),
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
}
