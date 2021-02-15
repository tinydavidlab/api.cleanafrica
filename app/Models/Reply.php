<?php

namespace App\Models;

use App\Traits\ReplyRelations;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use ReplyRelations;

    protected $fillable = [
        'content', 'ticket_id', 'photo',
        'replyable_type', 'replyable_id'
    ];
}
