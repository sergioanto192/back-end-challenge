<?php
namespace App\Services\Interfaces;

interface CategoryServiceInterface {
    public function getAllCategories();
    public function getCategoryById($id);
    public function createCategory($data);
    public function updateCategory($id, $data);
    public function deleteCategory($id);
}