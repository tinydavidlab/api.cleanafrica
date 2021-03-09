<?php

namespace App\Listeners;

use App\Events\CollectorUpdatedTripEvent;
use App\Notifications\CollectorUpdatedTrip;
use Illuminate\Support\Facades\Notification;


class NotifyAdminCollectorUpdatedTrip
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
     * @param CollectorUpdatedTripEvent $event
     * @return void
     */
    public function handle( CollectorUpdatedTripEvent $event )
    {
        $admins = $event->trip->company->admins->filter( function ( $admin ) { return !is_null( $admin->device_token ); } );
        Notification::send( $admins, new CollectorUpdatedTrip( $event->trip ) );
    }
}
