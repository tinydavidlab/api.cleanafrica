<?php

namespace App\Models;

use App\Traits\AnnouncementProp;
use App\Traits\AnnouncementRelations;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use AnnouncementRelations;
    use AnnouncementProp;
    protected $fillable = [
        'type', 'title', 'content', 'photo',
        'priority','company_id'
    ];
}
