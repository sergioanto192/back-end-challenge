<?php
namespace App\Services;

use App\Models\Product;

class ProductDiscontService
{
    // Aplica um desconto dinâmico ao produto
    public static function applyDiscount($product, $discountPercentage)
    {
        // Garante que o desconto seja um número válido entre 0 e 100
        $discountPercentage = max(0, min(100, $discountPercentage));

        // Calcula o novo preço
        if (is_object($product)) {
            $product->has_discount = $discountPercentage > 0;
            $product->discount_price = round($product->price * ((100 - $discountPercentage) / 100), 2);
        } elseif (is_array($product)) {
            $product['has_discount'] = $discountPercentage > 0;
            $product['discount_price'] = round($product['price'] * ((100 - $discountPercentage) / 100), 2);
        }

        return $product;
    }

    // Retorna todos os produtos com desconto aplicado
    public static function getAllWithDiscount($products, $discountPercentage)
    {
        return array_map(fn($product) => self::applyDiscount($product, $discountPercentage), $products);
    }

    // Retorna um único produto com desconto aplicado
    public static function getByIdWithDiscount($product, $discountPercentage)
    {
        return self::applyDiscount($product, $discountPercentage);
    }
}
