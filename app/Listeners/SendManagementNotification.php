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
     * @return void
     */
    public function handle( NewUserRegistered $event )
    {
        $company = $event->customer->company;

        Notification::send( $company->admins, new NewUserAlertManagement( $event->customer ) );
    }
}
