<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPoll extends Model
{
    use SoftDeletes;

    protected $table = 'event_polls';
    protected $fillable = ['event_id', 'poll_id'];
}
