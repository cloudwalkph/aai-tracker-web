<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events';
    protected $fillable = ['name', 'description', 'start_date', 'end_date'];

    public function uniqueId()
    {
        return \Hashids::encode($this->id);
    }

    public function originalId($value)
    {
        return \Hashids::decode($value)[0];
    }
}
