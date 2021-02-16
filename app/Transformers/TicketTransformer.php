<?php

namespace App\Transformers;

use App\Models\Ticket;
use Illuminate\Support\Arr;
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
            'snoocode' => Arr::get( $ticket->getAttribute( 'stamp' ), 'code' ),
            'day' => Arr::get( $ticket->getAttribute( 'stamp' ), 'day' ),
            'date' => Arr::get( $ticket->getAttribute( 'stamp' ), 'date' ),
            'time' => Arr::get( $ticket->getAttribute( 'stamp' ), 'time' ),
            'country' => Arr::get( $ticket->getAttribute( 'stamp' ), 'country' ),
            'subdivision' => Arr::get( $ticket->getAttribute( 'stamp' ), 'subdivision' ),
            'division' => Arr::get( $ticket->getAttribute( 'stamp' ), 'division' ),
            'latitude' => Arr::get( $ticket->getAttribute( 'stamp' ), 'latitude' ),
            'longitude' => Arr::get( $ticket->getAttribute( 'stamp' ), 'longitude' ),
            'signature' => Arr::get( $ticket->getAttribute( 'stamp' ), 'stamp_string' ),
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
