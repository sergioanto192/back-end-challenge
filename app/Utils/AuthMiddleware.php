<?php
namespace App\Utils;

use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Exception;
use Firebase\JWT\JWT; 
use App\Config\Config;
use App\Config\JwtHandler;

Config::loadEnv(__DIR__ . '/../../.env');
class AuthMiddleware {
    public static function authenticate() {
        $secretKey = getenv('JWT_SECRET');
        $algorithm = getenv('JWT_ALGORITHM');
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Token não fornecido']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        try {
            $decoded = JWT::decode($token, new Key($secretKey, $algorithm));
            return $decoded->sub;
        } catch (ExpiredException $e) { 
            http_response_code(401);
            return ['error' => 'Token expirado. Faça login novamente.'];

        } catch (Exception $e) { 
            http_response_code(401);
            return ['error' => 'Token inválido.'];
        }
    }
}