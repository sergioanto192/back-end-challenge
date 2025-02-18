<?php
namespace App\Handlers;

class ErrorHandler {
    public static function notFound($message = 'Recurso não encontrado') {
        http_response_code(404);
        return ['error' => $message];
    }

    public static function badRequest($message = 'Requisição inválida') {
        http_response_code(400);
        return ['error' => $message];
    }
    public static function unauthorized($message = 'Não autorizado') {
        http_response_code(401);
        return ['error' => $message];
    }
    public static function methodNotAccepted($message = 'Metodo não aceito') {
        http_response_code(response_code: 405);
        return ['error' => $message];
    }
    public static function requestTimeOut($message = 'Tempo de Requisição esgotado') {
        http_response_code(response_code: 408);
        return ['error' => $message];
    }
    public static function internalServerError($message = 'Erro interno de servidor') {
        http_response_code(response_code: 500);
        return ['error' => $message];
    }
}