<?php

namespace App\Transformers;

use App\Models\Ticket;
use App\Utilities\ImageUploader;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class TicketTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes
        = [
            'customer', 'admin', 'category', 'company',
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
        $stamp = $ticket->getAttribute( 'stamp' );
        if ( is_string( $ticket->getAttribute( 'stamp' ) ) ) {
            $stamp = json_decode( $stamp, true );
        }

        return [
            'id'            => $ticket->getAttribute( 'id' ),
            'category'      => $ticket->category->name ?? null,
            'customer_id'   => $ticket->getAttribute( 'customer_id' ),
            'customer_name' => $ticket->customer->name ?? null,
            'admin_id'      => $ticket->getAttribute( 'admin_id' ),
            'assigned_to'   => $ticket->admin->name ?? null,
            'subject'       => $ticket->getAttribute( 'subject' ),
            'content'       => $ticket->getAttribute( 'content' ),
            'photo'         => $this->getImageUrl( $ticket ),
            'priority'      => $ticket->getAttribute( 'priority' ),
            'status'        => $ticket->getAttribute( 'status' ),
            'snoocode'      => Arr::get( $stamp, 'code' ),
            'day'           => Arr::get( $stamp, 'day' ),
            'date'          => Arr::get( $stamp, 'date' ),
            'time'          => Arr::get( $stamp, 'time' ),
            'country'       => Arr::get( $stamp, 'country' ),
            'subdivision'   => Arr::get( $stamp, 'subdivision' ),
            'division'      => Arr::get( $stamp, 'division' ),
            'latitude'      => Arr::get( $stamp, 'latitude' ),
            'longitude'     => Arr::get( $stamp, 'longitude' ),
            'signature'     => Arr::get( $stamp, 'stamp_string' ),
            'created_at'    => $ticket->getAttribute( 'created_at' )->diffForHumans(),
        ];
    }

    public function getImageUrl( Ticket $ticket )
    {
        if ( $ticket->getAttribute( 'photo' ) == null ) {
            return null;
        }

        return ImageUploader::getFileURI( $ticket->getAttribute( 'photo' ), 'support_tickets' );
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
