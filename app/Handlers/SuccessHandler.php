<?php
namespace App\Handlers;

class SuccessHandler {
    public static function UpdateOperationSuccess($id) {
      
        return ["status" => "Sucesso ao atualizar o id: $id"];;
    }

    public static function DeleteOperationSuccess($id) {
      
        return ["status" => "Sucesso ao deletar o id: $id"];;
    }
    public static function DeleteOperationSuccessShowName($name) {
      
        return ["status" => "Sucesso ao deletar: $name"];;
    }
    public static function CreateOperationSuccess($id) {
      
        return ["status" => "Sucesso ao criar o id: $id"];;
    }
    public static function UnlinkOperationSuccess($firstId,$secondId) {
      
        return ["status" => "Sucesso ao desvinciliar o id: $firstId do id: $secondId"];;
    }
    public static function LinkOperationSuccess($firstId,$secondId) {
    if (is_array($secondId)) {
        $secondIdList = implode(', ', $secondId); 
        return ["status" => "Sucesso ao vincular o id: $firstId aos ids: $secondIdList"];
    }
    return ["status" => "Sucesso ao vincular o id: $firstId ao id: $secondId"];
    }
}