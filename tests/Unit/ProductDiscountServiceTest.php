<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ProductDiscontService;

class ProductDiscountServiceTest extends TestCase
{
    public function test_apply_discount_on_array_product()
    {
        $product = [
            'price' => 100.0
        ];
        
        $discountedProduct = ProductDiscontService::applyDiscount($product, 20);

        $this->assertArrayHasKey('has_discount', $discountedProduct);
        $this->assertArrayHasKey('discount_price', $discountedProduct);
        $this->assertTrue($discountedProduct['has_discount']);
        $this->assertEquals(80.0, $discountedProduct['discount_price']);
    }

    public function test_apply_discount_on_object_product()
    {
        $product = new \stdClass();
        $product->price = 200.0;

        $discountedProduct = ProductDiscontService::applyDiscount($product, 50);


        $this->assertTrue(property_exists($discountedProduct, 'discount_price'));
        $this->assertTrue($discountedProduct->has_discount);
        $this->assertEquals(100.0, $discountedProduct->discount_price);
    }

    public function test_get_all_with_discount()
    {
        $products = [
            ['price' => 50.0],
            ['price' => 150.0]
        ];

        $discountedProducts = ProductDiscontService::getAllWithDiscount($products, 10);

        $this->assertCount(2, $discountedProducts);
        $this->assertEquals(45.0, $discountedProducts[0]['discount_price']);
        $this->assertEquals(135.0, $discountedProducts[1]['discount_price']);
    }

    public function test_get_by_id_with_discount()
    {
        $product = ['price' => 100.0];

        $discountedProduct = ProductDiscontService::getByIdWithDiscount($product, 30);

        $this->assertEquals(70.0, $discountedProduct['discount_price']);
    }
}
