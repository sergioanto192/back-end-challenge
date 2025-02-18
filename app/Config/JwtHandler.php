<?php
namespace App\Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtHandler
{
    private $secretKey;
    private $algorithm;

    public function __construct()
    {
        // Carrega as variáveis de ambiente
        Config::loadEnv(__DIR__ . '/../../.env');

        // Define as configurações do JWT
        $this->secretKey = getenv('JWT_SECRET');
        $this->algorithm = getenv('JWT_ALGORITHM');
    }

    // Método para gerar um token JWT
    public function generateJWT($userId)
    {
        $payload = [
            'iss' => 'sua_api',       
            'sub' => $userId,         
            'iat' => time(),          
            'exp' => time() + 3600    // Token expira em 1 hora, para teste simples colocar 60
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    // Método para validar um token JWT
    public function validateJWT($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return $decoded->sub; 
        } catch (Exception $e) {
            return null; 
        }
    }
}