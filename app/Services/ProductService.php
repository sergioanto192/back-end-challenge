<?php
namespace App\Services;

use App\Services\Interfaces\ProductServiceInterface;

use App\Models\Product;


class ProductService implements ProductServiceInterface {
    //função para buscar todos, verifica e valida se existe disconto buscando apartir dos paramtros
    public function getAll($sortBy, $discountPercentage = null): array {
        if ($discountPercentage > 0) {
            $product = Product::getAll($sortBy);
            return ProductDiscontService::getAllWithDiscount($product, $discountPercentage);
        } else {
            return Product::getAll($sortBy);
        }
        
    }

     //função para buscar id, verifica e valida se existe disconto buscando apartir dos paramtros
    public function getById($id,$discountPercentage = null) {
        if ($discountPercentage > 0) {
        $product =  Product::findById($id);
        return ProductDiscontService::getByIdWithDiscount($product, $discountPercentage);
        } else {
            return Product::findById($id);
        }
    }

    //função para buscar id de categoria, verifica e valida se existe disconto buscando apartir dos paramtros
    public function getByCategory($categoryId, $discountPercentage = null) {
        if ($discountPercentage > 0) {
        $product =  Product::findByCategory($categoryId);
        if (is_array($product)) {
            return ProductDiscontService::getAllWithDiscount($product, $discountPercentage);
        } 
        elseif (is_object($product)) {
            return ProductDiscontService::getByIdWithDiscount($product, $discountPercentage);
        }
        }else {
        return Product::findByCategory($categoryId);
        }
    }

    public function create($data) {
        return Product::create($data['name'], $data['description'] ?? '', $data['price'], $data['category_id'], $data['likes']  ?? '0', $data['dislikes']  ?? '0');
    }

    public function update($id, $data) {
        return Product::update($id, $data);
    }

    public function delete($id) {
        return Product::delete($id);
    }
}