<?php
namespace App\Controllers;

use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;

class CategoryController {

    protected $categoryService;
    protected $authService;
    protected $authError;

    public function __construct(CategoryServiceInterface $categoryService , AuthServiceInterface $authService) {
        $this->categoryService = $categoryService;
        $this->authService = $authService;

        $this->authError = $this->authService->authenticate();
    }
    //Metodo que exibe todos as categorias
    public function index() {
        if ($this->authError) return $this->authError;
        return $this->categoryService->getAllCategories();
    }
    //Metodo que exibe uma categoria especifica
    public function show($id) {
        if ($this->authError) return $this->authError;
        return $this->categoryService->getCategoryById($id);
    }
    //Metodo que exibe cria uma categoria
    public function store($data) {
        if ($this->authError) return $this->authError;
        return $this->categoryService->createCategory($data);
    }
    //Metodo que exibe atualiza categoria
    public function update($id, $data) {
        if ($this->authError) return $this->authError;
        return $this->categoryService->updateCategory($id, $data);
    }
    //Metodo que exibe deleta categoria
    public function destroy($id) {
        if ($this->authError) return $this->authError;
        return $this->categoryService->deleteCategory($id);
    }
}
