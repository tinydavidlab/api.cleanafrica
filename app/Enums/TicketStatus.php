<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OPEN()
 * @method static static CLOSED()
 */
final class TicketStatus extends Enum
{
    const OPEN   = 'OPEN';
    const CLOSED = 'CLOSED';
}
