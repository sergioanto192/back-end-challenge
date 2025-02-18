<?php
namespace App\Utils;
class ResponseHandler {
    public static function success($data = [], $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode(['status' => 'success', 'data' => $data]);
    }

    public static function error($message, $statusCode = 400) {
        http_response_code($statusCode);
        echo json_encode(['status' => 'error', 'message' => $message]);
    }
}