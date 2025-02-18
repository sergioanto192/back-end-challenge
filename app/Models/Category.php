<?php
namespace App\Models;

use App\Config\db;
use \PDO;

class Category {

    // Método para conectar ao banco de dados
    private static function connect() {
        return db::connect(); // Obtém a conexão com o banco de dados
    }

    // Método para obter todas as categorias
    public static function getAll() {
        $stmt = self::connect()->query('SELECT * FROM store.categories');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para encontrar uma categoria por ID
    public static function findById($id) {
        $stmt = self::connect()->prepare('SELECT * FROM store.categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para criar uma nova categoria
    public static function create($name) {
        $stmt = self::connect()->prepare('INSERT INTO store.categories (name) VALUES (?)');
        $stmt->execute([$name]);
        return self::connect()->lastInsertId();
    }

    // Método para atualizar uma categoria existente
    public static function update($id, $name) {
        $stmt = self::connect()->prepare('UPDATE store.categories SET name = ? WHERE id = ?');
        return $stmt->execute([$name, $id]);
    }

    // Método para deletar uma categoria
    public static function delete($id) {
        $stmt = self::connect()->prepare('DELETE FROM store.categories WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

