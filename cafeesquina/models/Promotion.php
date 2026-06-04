<?php

declare(strict_types=1);

class Promotion
{
    public function active(): array
    {
        return db()->query(
            "SELECT * FROM promotions WHERE active = 1 AND start_date <= CURDATE() AND end_date >= CURDATE() ORDER BY end_date"
        )->fetchAll();
    }

    public function all(): array
    {
        return db()->query('SELECT * FROM promotions ORDER BY start_date DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $s = db()->prepare('SELECT * FROM promotions WHERE id = ?');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function create(array $d): bool
    {
        $s = db()->prepare('INSERT INTO promotions (title,description,image,start_date,end_date,active) VALUES (?,?,?,?,?,?)');
        return $s->execute([$d['title'], $d['description'], $d['image'], $d['start_date'], $d['end_date'], $d['active']]);
    }

    public function update(int $id, array $d): bool
    {
        $s = db()->prepare('UPDATE promotions SET title=?,description=?,image=?,start_date=?,end_date=?,active=? WHERE id=?');
        return $s->execute([$d['title'], $d['description'], $d['image'], $d['start_date'], $d['end_date'], $d['active'], $id]);
    }

    public function delete(int $id): bool
    {
        $s = db()->prepare('DELETE FROM promotions WHERE id = ?');
        return $s->execute([$id]);
    }

    public function countActive(): int
    {
        return (int) db()->query(
            "SELECT COUNT(*) FROM promotions WHERE active=1 AND start_date<=CURDATE() AND end_date>=CURDATE()"
        )->fetchColumn();
    }
}
