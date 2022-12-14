<?php

namespace App\Notifications;

use App\Models\Reply;
use App\Transformers\ReplyTransformer;
use App\Transformers\TicketTransformer;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerRepliedAdmin extends Notification
{
    use Queueable;

    /**
     * @var Reply
     */
    private $reply;

    /**
     * Create a new notification instance.
     *
     * @param Reply $reply
     */
    public function __construct( Reply $reply )
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via( $notifiable )
    {
        return [ 'database', 'fcm' ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail( $notifiable )
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
    public function toArray( $notifiable )
    {
        return [
            'title' => "Ticket Reply",
            'body' => "{$this->reply->replyable->name} sent a reply on {$this->reply->ticket->subject} ",

            'data' => [
                'reply' => fractal( $this->reply, new ReplyTransformer() )->withResourceName( 'replies' )->toArray(),
            ]
        ];
    }

    public function toFcm( $notifiable ): FcmMessage
    {
        $message = new FcmMessage();
        $message->content( [
            'title' => "Ticket Reply",
            'body' => " {$this->reply->replyable->name} sent a reply on {$this->reply->ticket->subject} ",
            'sound' => '',       // Optional
            'icon' => '',        // Optional
            'click_action' => '' // Optional
        ] )->data( [
            'type' => 'reply',
            'data' => [
                'reply' => fractal( $this->reply, new ReplyTransformer() )->withResourceName( 'replies' )->toArray(),
                'ticket' => fractal($this->reply->ticket, new TicketTransformer())->withResourceName('tickets')->toArray()
            ]
        ] )->priority( FcmMessage::PRIORITY_HIGH );

        return $message;
    }
}
