<?php
namespace App\AAI\Modules\EventAnswers\Repositories;

use App\AAI\Abstracts\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EventAnswersRepository extends EloquentRepository {
    protected $model;

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }
}