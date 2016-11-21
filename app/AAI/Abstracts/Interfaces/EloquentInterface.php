<?php
namespace App\AAI\Abstracts\Interfaces;

interface EloquentInterface {
    public function all();
    public function findById($id);
    public function findByKey($key, $value, $expression = '=');
    public function update($id, $data);
    public function destroy($id);
}