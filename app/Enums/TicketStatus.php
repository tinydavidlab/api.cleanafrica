<?php

namespace App\Enums;


/**
 * @method static static OPEN()
 * @method static static CLOSED()
 */
enum TicketStatus: string
{
    case OPEN = "OPEN";
    case CLOSED = "CLOSED";
}
