<?php


namespace App\Traits;


use Illuminate\Notifications\Notification;

trait CustomerProps
{
    use LatLongProp;

    /**
     * Route notifications for the FCM channel.
     *
     * @param Notification $notification
     * @return string
     */
    public function routeNotificationForFcm( Notification $notification ): string
    {
        return $this->getAttribute( 'device_token' );
    }
}
