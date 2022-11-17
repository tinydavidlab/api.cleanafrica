<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Notifications\NewUserAlertManagement;
use Illuminate\Support\Facades\Notification;

class SendManagementNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param NewUserRegistered $event
     *
     * @return void
     */
    public function handle( NewUserRegistered $event ): void
    {
        $admins = $event->customer->company->admins->filter( function ( $admin ) {
            return !is_null( $admin->device_token );
        } );
        Notification::send( $admins, new NewUserAlertManagement( $event->customer ) );
    }
}
