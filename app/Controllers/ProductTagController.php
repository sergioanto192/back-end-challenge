<?php
namespace App\Controllers;

use App\Services\Interfaces\ProductTagServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;
use App\Models\ProductTag;
use App\Handlers\ErrorHandler;
use App\Handlers\SuccessHandler;

class ProductTagController {


        private $ProductTagService;
        protected $authService;
        protected $authError;    
    
        public function __construct( ProductTagServiceInterface $ProductTagService , AuthServiceInterface $authService) {
            $this->ProductTagService = $ProductTagService;
            $this->authService = $authService;

            $this->authError = $this->authService->authenticate();
        }
    
        public function link($product_id, $tag_ids) {
            if ($this->authError) return $this->authError;
            // Chama o serviço de tags para associar as tags ao produto
            $this->ProductTagService->linkTagsToProduct($product_id, $tag_ids);
            return SuccessHandler::LinkOperationSuccess($product_id, $tag_ids);
        }
    
        public function unlink($product_id, $tag_id) {
            if ($this->authError) return $this->authError;
            // Chama o serviço de tags para desassociar a tag do produto
            $this->ProductTagService->unlinkTagFromProduct($product_id, $tag_id);
            return SuccessHandler::UnlinkOperationSuccess($product_id, $tag_id);
        }
    
        public function getTagsByProduct($product_id) {
            if ($this->authError) return $this->authError;
            // Chama o serviço de tags para obter as tags de um produto
            $tags = $this->ProductTagService->getTagsByProduct($product_id);
            if (empty($tags)) {
                return ErrorHandler::notFound('Nenhuma tag encontrada para esse produto');
            }
            return $tags;
        }
    
        public function getProductsByTag($tag_id) {
            if ($this->authError) return $this->authError;
            // Chama o serviço de tags para obter os produtos associados a uma tag
            $products = $this->ProductTagService->getProductsByTag($tag_id);
            if (empty($products)) {
                return ErrorHandler::notFound('Nenhum produto encontrado para essa categoria');
            }    
            return $products;
        }
    }
    

