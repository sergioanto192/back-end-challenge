<?php
namespace App\Models;


use App\Config\db;
use \PDO;

class Product {

    private static function connect() {
        return db::connect(); 
    }

    public static function getAll($sortBy ) {
        $sql = "SELECT p.*, c.name AS category_name,
           COALESCE(STRING_AGG(t.name, ', '), '') AS tags        
            FROM store.products p
            INNER JOIN store.categories c ON p.category_id = c.id
            LEFT JOIN store.product_tags pt ON p.id = pt.product_id
            LEFT JOIN store.tags t ON pt.tag_id = t.id
            GROUP BY p.id, c.name
            ORDER BY p.$sortBy ASC";
    
        $stmt = self::connect()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function findById($id) {
        $stmt = self::connect()->prepare("SELECT p.*, c.name AS category_name, 
            COALESCE(STRING_AGG(t.name, ', '), '') AS tags 
            FROM store.products p
            INNER JOIN store.categories c ON p.category_id = c.id
            LEFT JOIN store.product_tags pt ON p.id = pt.product_id
            LEFT JOIN store.tags t ON pt.tag_id = t.id 
            WHERE p.id = ?
            GROUP BY p.id, c.name");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByCategory($id) {
        $stmt = self::connect()->prepare('SELECT * FROM store.products WHERE category_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($name, $description, $price, $category_id, $likes, $dislikes) {
        $sql = 'INSERT INTO store.products (name, price, category_id';
        $values = 'VALUES (?, ?, ?';
        $params = [$name, $price, $category_id];
    
        if ($description !== null) {
            $sql .= ', description';
            $values .= ', ?';
            $params[] = $description;
        }
    
        if ($likes !== null) {
            $sql .= ', likes';
            $values .= ', ?';
            $params[] = $likes;
        }
    
        if ($dislikes !== null) {
            $sql .= ', dislikes';
            $values .= ', ?';
            $params[] = $dislikes;
        }
    
        $sql .= ') ' . $values . ')';
    
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        
        return self::connect()->lastInsertId();
    }

    public static function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        $sql = 'UPDATE store.products SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = self::connect()->prepare($sql);
        return $stmt->execute($values);
    }

    public static function delete($id) {
        $stmt = self::connect()->prepare('DELETE FROM store.products WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

  