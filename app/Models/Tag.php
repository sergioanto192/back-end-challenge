<?php
namespace App\Models;

use App\Config\db;
use \PDO;
use App\Exceptions\DatabaseException;

class Tag {

    // Método para conectar ao banco de dados
    private static function connect() {
        return db::connect(); 
    }

    // Método para obter todas as tags
    public static function getAll() {
        try {
            $stmt = self::connect()->query('SELECT * FROM store.tags');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new DatabaseException('Erro ao obter todas as tags: ' . $e->getMessage(), $e->getCode(), $e->getMessage());
        }
    }

    // Método para encontrar uma tag por ID
    public static function findById($id) {
        try {
            $stmt = self::connect()->prepare('SELECT * FROM store.tags WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new DatabaseException('Erro ao buscar a tag pelo ID: ' . $e->getMessage(), $e->getCode(), $e->getMessage());
        }
    }

    // Método para criar uma nova tag
    public static function create($name) {
        try {
            $stmt = self::connect()->prepare('INSERT INTO store.tags (name) VALUES (?)');
            $stmt->execute([$name]);
            return self::connect()->lastInsertId();
        } catch (\PDOException $e) {
            throw new DatabaseException('Erro ao criar a tag: ' . $e->getMessage(), $e->getCode(), $e->getMessage());
        }
    }

    // Método para atualizar uma tag existente
    public static function update($id, $name) {
        try {
            $stmt = self::connect()->prepare('UPDATE store.tags SET name = ? WHERE id = ?');
            $stmt->execute([$name, $id]);
            return $stmt->rowCount(); // Retorna o número de linhas afetadas
        } catch (\PDOException $e) {
            throw new DatabaseException('Erro ao atualizar a tag: ' . $e->getMessage(), $e->getCode(), $e->getMessage());
        }
    }

    // Método para deletar uma tag
    public static function delete($id) {
        try {
            $stmt = self::connect()->prepare('DELETE FROM store.tags WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->rowCount(); // Retorna o número de linhas afetadas
        } catch (\PDOException $e) {
            throw new DatabaseException('Erro ao deletar a tag: ' . $e->getMessage(), $e->getCode(), $e->getMessage());
        }
    }
}

