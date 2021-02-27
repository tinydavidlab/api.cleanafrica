<?php

namespace App\Models;

use App\Traits\TicketRelations;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use TicketRelations;

    protected $fillable = [
        'status', 'customer_id',
        'subject', 'content',
        'priority', 'agent_id',
        'category_id', 'stamp', 'photo'
    ];

    protected $casts = [ 'stamp' => 'array' ];
}
