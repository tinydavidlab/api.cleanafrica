<?php

namespace App\Events;

use App\Models\Announcement;

class SendAnnouncementToCollector extends Event
{
    /**
     * Create a new event instance.
     *
     * @param Announcement $announcement
     */
    public function __construct( public Announcement $announcement )
    {
    }
}
