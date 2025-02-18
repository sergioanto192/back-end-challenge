<?php
namespace App\Models;

require_once __DIR__ . '/../Config/db.php';
use App\Config\db;
use \PDO;          
use \PDOException; 

class User {
    private static function connect() {
        return db::connect(); // Usa a classe correta para conectar
    }

    // Buscar usuário por email
    public static function findByEmail($email) {
        try {
            $stmt = self::connect()->prepare('SELECT * FROM store.users WHERE email = ?');
            $stmt->execute([$email]);
            return $stmt->fetch(\PDO::FETCH_ASSOC); // Retorna o usuário ou false se não encontrado
        } catch (\PDOException $e) {
            die("Erro ao buscar o usuário: " . $e->getMessage());
        }
    }

    // Buscar usuário por ID
    public static function findById($id) {
        try {
            $stmt = self::connect()->prepare('SELECT * FROM store.users WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Erro ao buscar o usuário: " . $e->getMessage());
        }
    }

    // Criar um novo usuário
    public static function create($name, $email, $password_hash) {
        try {
            $stmt = self::connect()->prepare('
                INSERT INTO store.users (name, email, password_hash, created_at)
                VALUES (?,?, ?, NOW())
            ');
            $stmt->execute([ $name, $email, $password_hash, ]);
            return self::connect()->lastInsertId(); // Retorna o ID do usuário recém-criado
        } catch (\PDOException $e) {
            die("Erro ao criar o usuário: " . $e->getMessage());
        }
    }
}

