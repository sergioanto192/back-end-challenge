<?php

use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\ProductTagService;
use App\Services\AuthService;
use App\Services\TagService;
use App\Controllers\TagController;
use App\Controllers\CategoryController;
use App\Controllers\ProductTagController;
use App\Controllers\UserController;
use App\Utils\AuthMiddleware;
use App\Handlers\ErrorHandler;



$method = $_SERVER['REQUEST_METHOD'];
//Para poder ser testando tanto em container quanto em servidor embutido foi criado esta tratativa.
if (php_sapi_name() === 'cli-server') {
    // Servidor embutido do PHP
    $request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
} else {
    // Apache/Docker
    $request = explode('/', trim($_GET['url'] ?? '', '/'));
}


switch ($request[0]) {
    case 'login':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode(AuthController::login($data));
        }
        break;
    case 'register':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            // Chama o método do UserController para criar o usuário
            $response = UserController::store($data);

            // Retorna a resposta do controller
            echo json_encode($response);
        }
        break;


    case 'products':
        AuthMiddleware::authenticate();
        $productService = new ProductService();
        $authService = new AuthService();
        $productController = new ProductController($productService, $authService);
        switch ($method) {
            case 'GET':
                // Verifica se está buscando produtos por categoria GET = /products/categories/[id]
                if (isset($request[1]) && $request[1] === 'categories' && isset($request[2]) && is_numeric($request[2])) {
                    $categoryId = (int) $request[2];
                    echo json_encode($productController->getByCategory($categoryId));
                }
                // Verifica se está buscando um produto específico GET = /products/[id]
                elseif (isset($request[1]) && is_numeric($request[1])) {
                    echo json_encode($productController->show((int) $request[1]));
                }
                // Caso contrário, retorna todos os produtos com possível desconto GET /products ou /products?sortBy=[sort_type] ou products?discont=[descont_percentage] 
                else {
                    echo json_encode($productController->index());
                }
                break;

            case 'POST':
                // Cria um novo produto POST = /products
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($productController->store($data));
                break;

            case 'PUT':
                // Atualiza os dados de um produto existente UPDATE = /products/[id]
                if (isset($request[1]) && is_numeric($request[1])) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo json_encode($productController->update($request[1], $data));
                }
                break;

            case 'DELETE':
                // Deleta um produto DELETE = /products/[id]
                if (isset($request[1]) && is_numeric($request[1])) {
                    echo json_encode($productController->destroy($request[1]));
                }
                break;

            default:
                // Caso o método HTTP não seja reconhecido
                http_response_code(405);
                echo json_encode(['error' => 'Method Not Allowed']);
                break;
        }
        break;
    case 'tags':
        AuthMiddleware::authenticate();
        $tagService = new TagService();
        $authService = new AuthService();
        $tagController = new TagController($tagService, $authService);
        if ($method === 'GET') {
            if (isset($request[1]) && is_numeric($request[1])) {
                echo json_encode($tagController->show($request[1]));
            } else {
                echo json_encode($tagController->index());
            }
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($tagController->store($data));
        } elseif ($method === 'PUT' && isset($request[1]) && is_numeric($request[1])) {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($tagController->update($request[1], $data));
        } elseif ($method === 'DELETE' && isset($request[1]) && is_numeric($request[1])) {
            echo json_encode($tagController->destroy($request[1]));
        }
        break;

    case 'categories':
        AuthMiddleware::authenticate();
        $categoryService = new CategoryService();
        $authService = new AuthService();
        $categoryController = new CategoryController($categoryService, $authService);

        if ($method === 'GET') {
            if (isset($request[1]) && is_numeric($request[1])) {
                echo json_encode($categoryController->show($request[1]));
            } else {
                echo json_encode($categoryController->index());
            }
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($categoryController->store($data));
        } elseif ($method === 'PUT' && isset($request[1]) && is_numeric($request[1])) {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($categoryController->update($request[1], $data));
        } elseif ($method === 'DELETE' && isset($request[1]) && is_numeric($request[1])) {
            echo json_encode($categoryController->destroy($request[1]));
        }
        break;

    case 'productsTag':
        AuthMiddleware::authenticate();
        $productTagService = new ProductTagService();
        $authService = new AuthService();
        $productTagController = new ProductTagController($productTagService, $authService);
        if (isset($request[1]) && is_numeric($request[1])) {
            // Se o segundo parâmetro for "tags", busca tags de um produto GET /productsTag/[product_id]/tags
            if ($request[2] === 'tags') {
                if ($method === 'GET') {
                    echo json_encode($productTagController->getTagsByProduct($request[1]));
                } elseif ($method === 'POST')
                /// vincular tags a um produto POST productsTag/[product_id]/tags
                {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo json_encode($productTagController->link($request[1], $data['tag_ids']));
                }
                // Se o segundo parâmetro for um número e o terceiro "tags", queremos remover uma tag de um produto
                elseif ($method === 'DELETE' && isset($request[3]) && is_numeric($request[3])) {
                    echo json_encode($productTagController->unlink($request[1], $request[3]));
                }
            }
        }
        // Buscar produtos por uma tag específica GET /productsTag/products/[tag_id]
        elseif (isset($request[1]) && $request[1] === 'products' && isset($request[2]) && is_numeric($request[2])) {
            if ($method === 'GET') {
                echo json_encode($productTagController->getProductsByTag($request[2]));
            }
        }
        break;

    default:
        echo json_encode(ErrorHandler::notFound('Nenhum endpoint encontrado'));
        break;
}
