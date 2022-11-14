<?php

namespace App\Models;

use App\Traits\TruckRelations;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use TruckRelations;

    protected $fillable = [
        'name',
        'company_id',
        'license_number',
    ];
}
