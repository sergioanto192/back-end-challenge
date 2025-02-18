<?php
namespace App\Services\Interfaces;

interface ProductTagServiceInterface {
    public function linkTagsToProduct($product_id, $tag_ids);
    public function unlinkTagFromProduct($product_id, $tag_id);
    public function getTagsByProduct($product_id);
    public function getProductsByTag($tag_id);
}