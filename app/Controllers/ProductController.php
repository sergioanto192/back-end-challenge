<?php
namespace App\Controllers;

use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;
use App\Handlers\ErrorHandler;
use App\Handlers\SuccessHandler;
use App\Validators\ProductControllerValidator;


class ProductController {

    private $productService;
    protected $authService;
    protected $authError;
    
    public function __construct(ProductServiceInterface $productService , AuthServiceInterface $authService) {
        $this->productService = $productService;
        $this->authService = $authService;

        $this->authError = $this->authService->authenticate();
    }

    // Método para listar todos os produtos, o metodo avalia se é com o sem desconto
    public function index() {
        if ($this->authError) return $this->authError;
        $sortBy = ProductControllerValidator::validateSortBy($_GET['sortBy'] ?? 'name');
        $discountPercentage = ProductControllerValidator::validateDiscount($_GET['discount'] ?? 0);
        
        return $this->productService->getAll($sortBy, $discountPercentage);
    }

    // Método para mostrar um produto específico
    public function show($id) {
        if ($this->authError) return $this->authError;
        $discountPercentage = ProductControllerValidator::validateDiscount($_GET['discount'] ?? 0);
      
        $product = $this->productService->getById($id , $discountPercentage);

        if (!$product) {
            return ErrorHandler::notFound('Produto não encontrado');
        }
        return $product;
    }
 // Método para mostrar um produto por categoria 
    public function getByCategory($categoryId) {
        if ($this->authError) return $this->authError;
        $discountPercentage = ProductControllerValidator::validateDiscount($_GET['discount'] ?? 0);
        $products = $this->productService->getByCategory($categoryId,$discountPercentage);
        if (empty($products)) {
            return ErrorHandler::notFound('Nenhum produto encontrado para essa categoria');
        }

        return $products;
    }

    // Método para criar um novo produto
    public function store($data) {

        if (!isset($data['name']) || !isset($data['price']) || !isset($data['category_id'])) {
            return ErrorHandler::badRequest('Nome, preço e categoria são obrigatórios');
        }

        $product_id = $this->productService->create($data);
        return SuccessHandler::CreateOperationSuccess($product_id);
    }

    // Método para atualizar um produto existente
    public function update($id, $data) {
        if ($this->authError) return $this->authError;
        $product = $this->productService->getById($id, null);
        if (!$product) {
            return ErrorHandler::notFound('Produto não encontrado');
        }

        $this->productService->update($id, $data);
        return SuccessHandler::UpdateOperationSuccess($id);
    }

    // Método para excluir um produto
    public function destroy($id) {
        if ($this->authError) return $this->authError;
        $product = $this->productService->getById($id, null);
        if (!$product) {
            return ErrorHandler::notFound('Produto não encontrado');
        }

        $this->productService->delete($id);
        return SuccessHandler::DeleteOperationSuccess($id);
    }
}
