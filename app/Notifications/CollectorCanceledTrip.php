<?php

namespace App\Notifications;

use App\Models\Trip;
use App\Transformers\TripTransformer;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollectorCanceledTrip extends Notification
{
    use Queueable;

    /**
     * @var Trip
     */
    private $trip;

    /**
     * Create a new notification instance.
     *
     * @param Trip $trip
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
            return [
                'title' => "A Trip was canceled",
                'body' => "'{$this->trip->customer_name}' trip was canceled",
                'data' => [
                    'type' => 'trip',
                    'trip' => fractal($this->trip, new TripTransformer())->withResourceName('trips')->toArray()
                ]
        ];
    }

    public function toFcm( $notifiable ): FcmMessage
    {
        $message = new FcmMessage();
        $message->content( [
            'title' => 'A Trip was canceled',
            'body' => " '{$this->trip->customer_name}' trip was canceled",
            'sound' => '',       // Optional
            'icon' => '',        // Optional
            'click_action' => '' // Optional
        ] )->data( [
            'type' => 'trip',
            'data' => [
                'trip' => fractal($this->trip, new TripTransformer())->withResourceName('trips')->toArray()

            ]
        ] )->priority( FcmMessage::PRIORITY_HIGH );

        return $message;
    }
}
