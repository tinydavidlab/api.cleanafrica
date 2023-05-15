<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static LOW()
 * @method static static MEDIUM()
 * @method static static URGENT()
 * @method static static VERY_URGENT()
 */
final class TicketPriority extends Enum
{
    const LOW         = 'LOW';
    const MEDIUM      = 'MEDIUM';
    const URGENT      = 'URGENT';
    const VERY_URGENT = 'VERY_URGENT';
}
