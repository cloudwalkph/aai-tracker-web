<?php
namespace App\AAI\Modules\EventPolls\Repositories;

use App\AAI\Abstracts\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EventPollsRepository extends EloquentRepository {
    protected $model;

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }
}