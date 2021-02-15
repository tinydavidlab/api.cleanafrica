<?php

namespace App\Transformers;

use App\Models\Ticket;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class TicketTransformer extends TransformerAbstract
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
        'customer', 'admin', 'category'
    ];

    /**
     * A Fractal transformer.
     *
     * @param Ticket $ticket
     *
     * @return array
     */
    public function transform( Ticket $ticket ): array
    {
        return [
            'id' => $ticket->getAttribute( 'id' ),
            'category' => $ticket->category->name ?? null,
            'subject' => $ticket->getAttribute( 'subject' ),
            'content' => $ticket->getAttribute( 'content' ),
            'priority' => $ticket->getAttribute( 'priority' ),
            'status' => $ticket->getAttribute( 'status' ),
            'created_at' => $ticket->getAttribute( 'created_at' )->diffForHumans(),
        ];
    }

    public function includeCustomer( Ticket $ticket ): ?Item
    {
        $customer = $ticket->customer;
        if ( !$customer ) return null;

        return $this->item( $customer, new CustomerTransformer, 'customers' );
    }

    public function includeAdmin( Ticket $ticket ): ?Item
    {
        $admin = $ticket->admin;
        if ( !$admin ) return null;

        return $this->item( $admin, new AdminTransformer, 'admins' );
    }

    public function includeCategory( Ticket $ticket ): ?Item
    {
        $category = $ticket->category;
        if ( !$category ) return null;
        return $this->item( $ticket->category, new CategoryTransformer, 'categories' );
    }
}
