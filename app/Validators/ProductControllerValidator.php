<?php
namespace App\Validators;

class ProductControllerValidator {
    //Valida se o sort by foir definido e se é um dos possiveis valores, caso contrario padroniza name
    public static function validateSortBy($sortBy): string {
        $validSortColumns = ['name', 'price', 'likes'];
        return in_array($sortBy, $validSortColumns) ? $sortBy : 'name';
    }
//Valida se o valor de desconto é um numero valido
    public static function validateDiscount($discount): int {
        return max(0, (int) $discount);
    }
}