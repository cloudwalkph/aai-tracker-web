<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventLocationAnswer extends Model
{
    use SoftDeletes;

    protected $table = 'event_location_answers';
    protected $fillable = ['uuid', 'event_id', 'user_id',
        'event_location_id', 'image', 'hit_date', 'name',
        'email', 'contact_number'];
}
