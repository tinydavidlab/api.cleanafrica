<?php

namespace App\Notifications;

use App\Models\Announcement;
use App\Transformers\AnnouncementTransformer;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollectorAnnouncement extends Notification
{
    use Queueable;

    /**
     * @var Announcement
     */
    private $announcement;

    /**
     * Create a new notification instance.
     *
     * @param Announcement $announcement
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
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
            'title' => "{$this->announcement->title}",
            'body' => "{$this->announcement->content}",
            'data' => [
                'type' => 'announcement',
                'announcement' => fractal($this->announcement, new AnnouncementTransformer())->withResourceName('announcements')->toArray()
            ]
        ];
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $message->content( [
            'title' => "{$this->announcement->title}",
            'body' => "{$this->announcement->content}",
            'sound' => '',       // Optional
            'icon' => '',        // Optional
            'click_action' => '' // Optional
        ] )->data( [
            'type' => 'announcement',
            'data' => [
                'announcement' => fractal($this->announcement, new AnnouncementTransformer())->withResourceName('announcement')->toArray()

            ]
        ] )->priority( FcmMessage::PRIORITY_HIGH );

        return $message;
    }
}
