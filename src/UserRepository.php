<?php

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Login
    public function findByCardNumber(string $cardNumber)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM users
            WHERE card_number = ?
        ");
        $stmt->execute([$cardNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hämta user via id
    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM users
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Admin: lista alla users
    public function getAllUsers()
    {
        $stmt = $this->pdo->query("
            SELECT id, name, card_number, role, created_at
            FROM users
            ORDER BY id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
