<?php

declare(strict_types=1);

class User
{
    public function findByEmail(string $email): ?array
    {
        $s = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $s->execute([$email]);
        return $s->fetch() ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $s = db()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $s->execute([$username]);
        return $s->fetch() ?: null;
    }

    public function find(int $id): ?array
    {
        $s = db()->prepare('SELECT id, username, email, full_name, phone, role, created_at FROM users WHERE id = ?');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function all(): array
    {
        return db()->query('SELECT id, username, email, full_name, phone, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
    }

    public function create(string $username, string $email, string $password, string $role = 'client', ?string $fullName = null): bool
    {
        $s = db()->prepare('INSERT INTO users (username, email, password, role, full_name) VALUES (?,?,?,?,?)');
        return $s->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role, $fullName]);
    }

    public function updateProfile(int $id, string $fullName, string $phone, string $email): bool
    {
        $s = db()->prepare('UPDATE users SET full_name = ?, phone = ?, email = ? WHERE id = ?');
        return $s->execute([$fullName, $phone, $email, $id]);
    }

    public function update(int $id, array $data): bool
    {
        $s = db()->prepare('UPDATE users SET username=?, email=?, full_name=?, phone=?, role=? WHERE id=?');
        return $s->execute([
            $data['username'], $data['email'], $data['full_name'], $data['phone'], $data['role'], $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $s = db()->prepare('DELETE FROM users WHERE id = ? AND role != ?');
        return $s->execute([$id, 'admin']);
    }

    public function countAll(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
