<?php

declare(strict_types=1);

class Category
{
    public function all(): array
    {
        return db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $s = db()->prepare('SELECT * FROM categories WHERE id = ?');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function create(string $name, ?string $description): bool
    {
        $s = db()->prepare('INSERT INTO categories (name, description) VALUES (?,?)');
        return $s->execute([$name, $description]);
    }

    public function update(int $id, string $name, ?string $description): bool
    {
        $s = db()->prepare('UPDATE categories SET name=?, description=? WHERE id=?');
        return $s->execute([$name, $description, $id]);
    }

    public function delete(int $id): bool
    {
        $check = db()->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
        $check->execute([$id]);
        if ((int) $check->fetchColumn() > 0) {
            return false;
        }
        $s = db()->prepare('DELETE FROM categories WHERE id = ?');
        return $s->execute([$id]);
    }

    public function countAll(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    }
}
