<?php

namespace App\Services\Interfaces;

interface TagServiceInterface {
    public function getAll();
    public function findById($id);
    public function create($name);
    public function update($id, $data);
    public function delete($id);
}
