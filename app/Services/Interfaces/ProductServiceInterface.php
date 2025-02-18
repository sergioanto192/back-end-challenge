<?php
namespace App\Services\Interfaces;

interface ProductServiceInterface {
    public function getAll($sortBy, $discountPercentage);
    public function getById($id, $discountPercentage);
    public function getByCategory($categoryId, $discountPercentage);
    public function create($data);
    public function update($id, $data);
    public function delete($id);

}