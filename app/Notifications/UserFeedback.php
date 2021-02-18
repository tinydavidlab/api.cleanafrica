<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\Ticket;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserFeedback extends Notification
{
    use Queueable;

    /**
     * @var Customer
     */
    public $customer;
    /**
     * @var Ticket
     */
    public $ticket;

    /**
     * Create a new notification instance.
     *
     * @param Customer $customer
     * @param Ticket $ticket
     */
    public function __construct( Customer $customer, Ticket $ticket )
    {
        $this->customer = $customer;
        $this->ticket   = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via( $notifiable ): array
    {
        return [ 'database', 'fcm' ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail( $notifiable ): MailMessage
    {
        return ( new MailMessage )
            ->line( 'The introduction to the notification.' )
            ->action( 'Notification Action', url( '/' ) )
            ->line( 'Thank you for using our application!' );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray( $notifiable ): array
    {
        return [
            //
        ];
    }

    public function toFcm( $notifiable ): FcmMessage
    {
        $message = new FcmMessage();
        $message->content( [
            'title' => 'Customer Registration',
            'body' => 'A new customer has just registered',
            'sound' => '',       // Optional
            'icon' => '',        // Optional
            'click_action' => '' // Optional
        ] )->data( [
            'type' => 'customer',
            'data' => $this->customer
        ] )->priority( FcmMessage::PRIORITY_HIGH );

        return $message;
    }
}
