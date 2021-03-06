<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAnswer extends Model
{
    use SoftDeletes;

    protected $table = 'event_answers';
    protected $fillable = ['event_location_answer_id', 'poll_id', 'value'];
}
