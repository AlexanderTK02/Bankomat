<?php

class TransactionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Logga transaktion
    public function log($fromId, $toId, $type, $amount)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO transactions (
                from_account_id,
                to_account_id,
                type,
                amount
            )
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([$fromId, $toId, $type, $amount]);
    }

    public function getByUser($userId)
    {
        $sql = "
        SELECT t.*
        FROM transactions t
        WHERE 
            t.from_account_id IN (SELECT id FROM accounts WHERE user_id = ?)
            OR
            t.to_account_id IN (SELECT id FROM accounts WHERE user_id = ?)
        ORDER BY t.id DESC
        LIMIT 20
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: lista alla transaktioner
    public function getAllTransactions()
    {
        $stmt = $this->pdo->query("
            SELECT t.*,
                   fa.user_id AS from_user,
                   ta.user_id AS to_user
            FROM transactions t
            LEFT JOIN accounts fa ON t.from_account_id = fa.id
            LEFT JOIN accounts ta ON t.to_account_id = ta.id
            ORDER BY t.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
