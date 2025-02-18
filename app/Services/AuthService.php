<?php
namespace App\Services;

use App\Services\Interfaces\AuthServiceInterface;
use App\Utils\AuthMiddleware;
use App\Handlers\ErrorHandler; 
use Exception;
  
class AuthService implements AuthServiceInterface {
 
    public function authenticate(): ?array {
       //verifica a autenticação
       
            $user = AuthMiddleware::authenticate();
            if (isset($user['error'])) {
                return ErrorHandler::unauthorized($user);
           
           }
           return null; // Usuário autenticado com sucesso
        }

    }

