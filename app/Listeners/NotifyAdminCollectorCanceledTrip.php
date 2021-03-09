<?php

namespace App\Listeners;

use App\Events\CollectorCanceledTripEvent;
use App\Notifications\CollectorCanceledTrip;
use Illuminate\Support\Facades\Notification;

class NotifyAdminCollectorCanceledTrip
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
     * @param  CollectorCanceledTripEvent  $event
     * @return void
     */
    public function handle(CollectorCanceledTripEvent $event)
    {
        $admins = $event->trip->company->admins->filter(function ($admin){return !is_null($admin->device_token);});
        Notification::send($admins, new CollectorCanceledTrip($event->trip));
    }
}
