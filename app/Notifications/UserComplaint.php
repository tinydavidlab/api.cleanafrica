<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\Ticket;
use App\Transformers\CustomerTransformer;
use App\Transformers\TicketTransformer;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserComplaint extends Notification
{
    use Queueable;

    /**
     * @var Customer
     */
    private $customer;
    /**
     * @var Ticket
     */
    private $ticket;

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
            'title' => 'Customer Complaint',
            'body' => 'One of your customers has just made a complaint.',
            'data' => [
                'customer' => $this->customer,
                'ticket' => $this->ticket
            ]
        ];
    }

    public function toFcm( $notifiable ): FcmMessage
    {
        $message = new FcmMessage();
        $message->content( [
            'title' => 'Customer Complaint',
            'body' => 'One of your customers has just made a complaint.',
            'sound' => '',       // Optional
            'icon' => '',        // Optional
            'click_action' => '' // Optional
        ] )->data( [
            'type' => 'complaint',
            'data' => [
                'customer' => fractal($this->customer, new CustomerTransformer())->withResourceName('customers')->toArray(),
                'ticket' => fractal($this->ticket, new TicketTransformer())->withResourceName('tickets')->toArray()
            ]
        ] )->priority( FcmMessage::PRIORITY_HIGH );

        return $message;
    }
}
