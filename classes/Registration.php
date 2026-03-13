<?php

class Registration
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM registrations ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM registrations WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data, string $profileImagePath): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO registrations
             (fname, lname, address, country, gender, skills, username, password, department, code, profile_image)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['fname'],
            $data['lname'],
            $data['address'],
            $data['country'],
            $data['gender'],
            json_encode($data['skills']),
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['department'],
            $data['code'],
            $profileImagePath,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE registrations
             SET fname = ?, lname = ?, address = ?, country = ?, gender = ?,
                 skills = ?, username = ?, department = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['fname'],
            $data['lname'],
            $data['address'],
            $data['country'],
            $data['gender'],
            json_encode($data['skills'] ?? []),
            $data['username'],
            $data['department'],
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM registrations WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
