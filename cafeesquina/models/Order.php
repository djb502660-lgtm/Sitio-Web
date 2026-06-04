<?php

declare(strict_types=1);

class Order
{
    public function log(?int $userId, int $productId, string $productName, float $price): bool
    {
        $s = db()->prepare(
            'INSERT INTO orders (user_id, product_id, product_name, price, channel) VALUES (?,?,?,?,\'whatsapp\')'
        );
        return $s->execute([$userId, $productId, $productName, $price]);
    }

    public function byUser(int $userId): array
    {
        $s = db()->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
        $s->execute([$userId]);
        return $s->fetchAll();
    }

    public function countAll(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    }

    public function recent(int $limit = 10): array
    {
        $s = db()->prepare(
            'SELECT o.*, u.username FROM orders o LEFT JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC LIMIT ?'
        );
        $s->bindValue(1, $limit, PDO::PARAM_INT);
        $s->execute();
        return $s->fetchAll();
    }
}
