<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{
    use SoftDeletes;

    protected $table = 'polls';
    protected $fillable = ['name', 'type', 'choices'];

    public function uniqueId()
    {
        return \Hashids::encode($this->id);
    }

    public function originalId($value)
    {
        return \Hashids::decode($value)[0];
    }
}
