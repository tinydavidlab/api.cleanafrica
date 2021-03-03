<?php

namespace App\Events;

use App\Models\Reply;

class CustomerRepliedTicket extends Event
{
    public $reply;

    /**
     * Create a new event instance.
     *
     * @param $reply
     */
    public function __construct( Reply $reply )
    {
        $this->reply = $reply;
    }
}
