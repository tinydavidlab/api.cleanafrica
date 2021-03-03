<?php

namespace App\Listeners;

use App\Events\CustomerRepliedTicket;
use App\Models\Admin;
use App\Notifications\CustomerRepliedAdmin;
use Illuminate\Support\Facades\Notification;

class NotifyAdminCustomerRepliedTicket
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CustomerRepliedTicket $event
     * @return void
     */
    public function handle( CustomerRepliedTicket $event )
    {
        $customer = $event->reply->replyable;

        $admins = $customer->company->admins;

        $admins = $admins->filter( function ( Admin $admin ) {
            return !is_null( $admin->getAttribute( 'device_token' ) );
        } );

        Notification::send( $admins, new CustomerRepliedAdmin( $event->reply ) );
    }
}
