<?php
namespace App\Controllers;
use  App\Models\User; 
use  App\Handlers\ErrorHandler;

class UserController {
    // Função para criar um novo usuário
    public static function store($data) {
       
        // Verificar se todos os campos obrigatórios foram fornecidos
        if (!isset($data['email']) || !isset($data['password']) || !isset($data['confirm_password']) || !isset($data['name'])) {
            return ErrorHandler::badRequest('Email, senha, confirmação de senha e nome são obrigatórios');
           
        }
    
        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
           return ErrorHandler::badRequest('Email inválido');
           
        }
    
        // Verificar se as senhas coincidem
        if ($data['password'] !== $data['confirm_password']) {
           return  ErrorHandler::badRequest('As senhas não coincidem');
           
        }
    
        // Verificar se o email já está em uso
        if (User::findByEmail($data['email'])) {
           return ErrorHandler::badRequest('Email já está em uso');
          
        }
    
        // Criar o hash da senha
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
    
        // Criar o usuário no banco de dados
        $user_id = User::create($data['name'], $data['email'], $password_hash );
    
        // Retornar resposta de sucesso
        return ['id' => $user_id, 'message' => 'Usuário criado com sucesso'];
    }
    
}

