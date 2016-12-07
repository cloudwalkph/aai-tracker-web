<?php
namespace App\AAI\Abstracts\Repositories;

use App\AAI\Abstracts\Interfaces\EloquentInterface;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentRepository implements EloquentInterface  {
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findByKey($key, $value, $expression = '=')
    {
        return $this->model->where($key, $expression, $value);
    }

    public function update($id, $data)
    {
        $this->model>where('id', $id)
            ->update($data);

        return $this->model->where('id', $id)->first();
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }

}