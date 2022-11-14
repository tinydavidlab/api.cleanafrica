<?php


namespace App\Traits;


use Illuminate\Notifications\Notification;

trait AnnouncementProp
{
    public function routeNotificationForFcm( Notification $notification ): string
    {
        return $this->getAttribute( 'device_token' );
    }
}
