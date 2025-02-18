<?php
namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Handlers\ErrorHandler;
use App\Handlers\SuccessHandler;
use App\Services\Interfaces\CategoryServiceInterface;


class CategoryService implements CategoryServiceInterface {

    public function getAllCategories() {
        $categories = Category::getAll();
        if (!$categories) {
            return ErrorHandler::notFound('Nenhuma categoria encontrada');
        }
        return $categories;
    }

    public function getCategoryById($id) {
        $category = Category::findById($id);
        if (!$category) {
            return ErrorHandler::notFound('Nenhuma categoria encontrada para este id');
        }
        return $category;
    }

    public function createCategory($data) {
        if (!isset($data['name'])) {
            return ErrorHandler::badRequest('Nome é obrigatório');
        }
        $category_id = Category::create($data['name']);
        return ['id' => $category_id];
    }

    public function updateCategory($id, $data) {
        $category = Category::findById($id);
        if (!$category) {
            return ErrorHandler::notFound('Nenhuma categoria encontrada para este id');
        }
        Category::update($id, $data['name']);
        return SuccessHandler::UpdateOperationSuccess($id);
    }

    public function deleteCategory($id) {
        $category = Category::findById($id);
        if (!$category) {
            return ErrorHandler::notFound('Nenhuma categoria encontrada para este id');
        }
        $products = Product::findByCategory($id);
        if (!empty($products)) {
            return ErrorHandler::badRequest('Categoria possui produtos atribuídos');
        }
        $deletedCategory = Category::delete($id);
        if ($deletedCategory) {
            return SuccessHandler::DeleteOperationSuccessShowName($category['name']);
        } else {
            return ErrorHandler::internalServerError('Erro ao tentar excluir');
        }
    }
}
