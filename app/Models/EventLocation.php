<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventLocation extends Model
{
    use SoftDeletes;

    protected $table = 'event_locations';
    protected $fillable = ['event_id', 'name'];
}
