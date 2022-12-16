<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Transformers\CustomerTransformer;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserAlertManagement extends Notification
{
    use Queueable;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * Create a new notification instance.
     *
     * @param Customer $customer
     */
    public function __construct( Customer $customer )
    {
        $this->customer = $customer;
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
            'title' => 'Customer Registration',
            'body' => 'A new customer has just registered',
            'data' => [
                'type' => 'customer',
                'customer' => fractal($this->customer, new CustomerTransformer())->withResourceName('customers')->toArray()
            ]
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
            'data' => [
                'customer' => fractal($this->customer, new CustomerTransformer())->withResourceName('customers')->toArray()
            ]
        ] )->priority( FcmMessage::PRIORITY_HIGH );

        return $message;
    }
}
