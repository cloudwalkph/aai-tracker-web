<?php
namespace App\AAI\Modules\Polls\Repositories;

use App\AAI\Abstracts\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Model;

class PollsRepository extends EloquentRepository {
    protected $model;

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }
}