<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAnswers extends Model
{
    use SoftDeletes;

    protected $table = 'event_answers';
    protected $fillable = ['event_id', 'poll_id', 'event_location_id', 'value'];
}
