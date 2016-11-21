<?php
namespace App\AAI\Modules\EventLocations\Repositories;

use App\AAI\Abstracts\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EventLocationsRepository extends EloquentRepository {
    protected $model;

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }
}