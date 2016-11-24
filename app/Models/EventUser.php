<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventUser extends Model
{
    use SoftDeletes;

    protected $table = 'event_users';
    protected $fillable = ['user_id', 'event_id'];
}
