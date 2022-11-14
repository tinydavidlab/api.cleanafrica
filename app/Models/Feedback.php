<?php

namespace App\Models;

use App\Traits\FeedbackRelations;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use FeedbackRelations;

    protected $fillable = [
        'message',
        'photo',
        'customer_id',
        'company_id',
        'device_id',
        'user_agent',
        'app_version',
        'brand',
        'manufacturer',
        'model',
        'stamp'
    ];

    protected $casts = [
        'stamp' => 'array'
    ];
}
