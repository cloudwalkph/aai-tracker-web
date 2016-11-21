<?php
namespace App\AAI\Modules\Events\Repositories;

use App\AAI\Abstracts\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EventsRepository extends EloquentRepository {
    protected $model;

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }
}