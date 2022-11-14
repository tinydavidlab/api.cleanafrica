<?php

namespace App\Events;

use App\Models\Announcement;

class SendAnnouncementToCollector extends Event
{
    /**
     * @var Announcement
     */
    public $announcement;

    /**
     * Create a new event instance.
     *
     * @param Announcement $announcement
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }
}
