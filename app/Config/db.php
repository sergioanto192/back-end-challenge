<?php
namespace App\Config;

use PDO;
use PDOException;
Config::loadEnv(__DIR__ . '/../../.env');
class db {
    private static $pdo = null;
   
    private function __construct() {} // Evita instância direta

    public static function connect() {
        if (!self::$pdo) {
            // Carregar variáveis de ambiente
            $host = getenv('DB_HOST');
            $dbname = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $password = getenv('DB_PASSWORD');
            $port = getenv('DB_PORT');

            // Verificar se as variáveis foram carregadas corretamente
            if (!$host || !$dbname || !$user || !$password || !$port) {
                die("Erro: Falha ao carregar as variáveis de ambiente do banco de dados.");
            }

            try {
                self::$pdo = new PDO(
                    "pgsql:host=$host;port=$port;dbname=$dbname",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
