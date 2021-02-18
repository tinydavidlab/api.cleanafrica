<?php

namespace App\Events;

use App\Models\Reply;

class AdminRepliedTicket extends Event
{
    /**
     * @var Reply
     */
    public $reply;

    /**
     * Create a new event instance.
     *
     * @param Reply $reply
     */
    public function __construct( Reply $reply )
    {
        $this->reply = $reply;
    }
}
