<?php
namespace App\Controllers;

use App\Config\JwtHandler;
use App\Handlers\ErrorHandler;
use App\Models\User; 

class AuthController {
    //classe resposavel pelo login
    public static function login($data) {
        $jwtHandler = new JwtHandler();
        if (!isset($data['email']) || !isset($data['password'])) {
           return  ErrorHandler::badRequest('Email e senha são obrigatórios');
        }

        $user = User::findByEmail($data['email']);
        if (!$user) {
           return  ErrorHandler::unauthorized('Usuário não encontrado');
        }
        
        if ($user && password_verify($data['password'], $user['password_hash'])) {
                // Chama a função generateJWT para gerar o token
                $validation = $jwtHandler->generateJWT($user['id']);
                return ['token' => $validation];
        } else {
            return  ErrorHandler::unauthorized('Credenciais inválidas');
        }
    }
}