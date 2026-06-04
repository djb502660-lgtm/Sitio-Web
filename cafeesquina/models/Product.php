<?php

declare(strict_types=1);

class Product
{
    public function all(?int $categoryId = null, ?string $search = null, ?string $status = null): array
    {
        $sql = 'SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE 1=1';
        $params = [];
        if ($categoryId) {
            $sql .= ' AND p.category_id = ?';
            $params[] = $categoryId;
        }
        if ($search) {
            $sql .= ' AND (p.name LIKE ? OR p.description LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($status) {
            $sql .= ' AND p.status = ?';
            $params[] = $status;
        }
        $sql .= ' ORDER BY p.created_at DESC';
        $s = db()->prepare($sql);
        $s->execute($params);
        return $s->fetchAll();
    }

    public function featured(int $limit = 6): array
    {
        $limit = max(1, min(20, $limit));
        $s = db()->prepare(
            "SELECT p.*, c.name AS category_name FROM products p
             JOIN categories c ON c.id = p.category_id
             WHERE p.featured = 1 AND p.status = 'available'
             ORDER BY p.created_at DESC LIMIT {$limit}"
        );
        $s->execute();
        return $s->fetchAll();
    }

    public function find(int $id): ?array
    {
        $s = db()->prepare(
            'SELECT p.*, c.name AS category_name FROM products p
             JOIN categories c ON c.id = p.category_id WHERE p.id = ?'
        );
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function create(array $d): bool
    {
        $s = db()->prepare(
            'INSERT INTO products (category_id,name,description,price,image,status,featured) VALUES (?,?,?,?,?,?,?)'
        );
        return $s->execute([
            $d['category_id'], $d['name'], $d['description'], $d['price'],
            $d['image'], $d['status'], $d['featured'] ?? 0,
        ]);
    }

    public function update(int $id, array $d): bool
    {
        $s = db()->prepare(
            'UPDATE products SET category_id=?,name=?,description=?,price=?,image=?,status=?,featured=? WHERE id=?'
        );
        return $s->execute([
            $d['category_id'], $d['name'], $d['description'], $d['price'],
            $d['image'], $d['status'], $d['featured'] ?? 0, $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $s = db()->prepare('DELETE FROM products WHERE id = ?');
        return $s->execute([$id]);
    }

    public function countAll(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
    }

    public function topSelling(int $limit = 5): array
    {
        $s = db()->prepare(
            'SELECT product_name, COUNT(*) AS total FROM orders GROUP BY product_name ORDER BY total DESC LIMIT ?'
        );
        $s->bindValue(1, $limit, PDO::PARAM_INT);
        $s->execute();
        return $s->fetchAll();
    }

    public function bestSellers(int $limit = 4): array
    {
        $limit = max(1, min(12, $limit));
        $sql = "SELECT p.*, c.name AS category_name FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE p.status = 'available'
                ORDER BY (SELECT COUNT(*) FROM orders o WHERE o.product_name = p.name) DESC
                LIMIT {$limit}";
        return db()->query($sql)->fetchAll();
    }
}
