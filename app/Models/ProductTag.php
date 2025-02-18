<?php
namespace App\Models;

use App\Config\db;
use \PDO;

class ProductTag {

    // Método para conectar ao banco de dados
    private static function connect() {
        return db::connect(); // Obtém a conexão com o banco de dados
    }

    // Método para vincular uma tag a um produto
    public static function link($product_id, $tag_id) {
        $stmt = self::connect()->prepare('INSERT INTO store.product_tags (product_id, tag_id) VALUES (?, ?)');
        $stmt->execute([$product_id, $tag_id]);
    }

    // Método para desvincular uma tag de um produto
    public static function unlink($product_id, $tag_id) {
        $stmt = self::connect()->prepare('DELETE FROM store.product_tags WHERE product_id = ? AND tag_id = ?');
        $stmt->execute([$product_id, $tag_id]);
    }

    // Método para obter todas as tags de um produto
    public static function getTagsByProduct($product_id) {
        $stmt = self::connect()->prepare('SELECT t.id, t.name FROM store.product_tags pt JOIN store.tags t ON pt.tag_id = t.id WHERE pt.product_id = ?');
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obter todos os produtos de uma tag
    public static function getProductsByTag($tag_id) {
        $stmt = self::connect()->prepare('SELECT p.id, p.name as product_name, p.description,	p.price,	c.name as category_name,	p.likes,	p.dislikes 
        FROM store.product_tags pt 
        JOIN store.products p ON pt.product_id = p.id  
        inner join 
        store.categories c on p.category_id = c.id
        WHERE pt.tag_id = ?');
        $stmt->execute([$tag_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

