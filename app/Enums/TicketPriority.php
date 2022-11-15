<?php

namespace App\Enums;

/**
 * @method static static LOW()
 * @method static static MEDIUM()
 * @method static static URGENT()
 * @method static static VERY_URGENT()
 */
enum TicketPriority: string
{
    case LOW = "LOW";
    case MEDIUM = "MEDIUM";
    case URGENT = "URGENT";
    case VERY_URGENT = "VERY_URGENT";
}
