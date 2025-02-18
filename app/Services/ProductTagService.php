<?php
namespace App\Services;

use App\Services\Interfaces\ProductTagServiceInterface;
use App\Models\ProductTag;

class ProductTagService implements ProductTagServiceInterface {
    public function linkTagsToProduct($product_id, $tag_ids) {
        foreach ($tag_ids as $tag_id) {
            ProductTag::link($product_id, $tag_id);
        }
    }

    public function unlinkTagFromProduct($product_id, $tag_id) {
        ProductTag::unlink($product_id, $tag_id);
    }

    public function getTagsByProduct($product_id) {
        return ProductTag::getTagsByProduct($product_id);
    }

    public function getProductsByTag($tag_id) {
        return ProductTag::getProductsByTag($tag_id);
    }
}


