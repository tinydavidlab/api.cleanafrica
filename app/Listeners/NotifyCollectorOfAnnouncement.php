<?php

namespace App\Listeners;

use App\Events\SendAnnouncementToCollector;
use App\Notifications\CollectorAnnouncement;
use Illuminate\Support\Facades\Notification;

class NotifyCollectorOfAnnouncement
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
     * @param SendAnnouncementToCollector $event
     *
     * @return void
     */
    public function handle( SendAnnouncementToCollector $event ): void
    {
        $collectors = $event->announcement->company->agents->filter( function ( $agent ) {
            return !is_null( $agent->device_token ) && $agent->type == 'collector';
        } );

        Notification::send( $collectors, new CollectorAnnouncement( $event->announcement ) );
    }
}
