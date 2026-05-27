<?php

class AccountRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Hämta ett konto via ID
    public function getAccountById(int $id)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM accounts
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hämta alla konton för en användare
    public function getAccountsByUser(int $userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM accounts
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: lista alla konton
    public function getAllAccounts()
    {
        $stmt = $this->pdo->query("
            SELECT accounts.*, users.name
            FROM accounts
            JOIN users ON accounts.user_id = users.id
            ORDER BY accounts.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insättning
    public function deposit(int $accountId, float $amount)
    {
        $stmt = $this->pdo->prepare("
            UPDATE accounts
            SET balance = balance + ?
            WHERE id = ?
        ");
        return $stmt->execute([$amount, $accountId]);
    }

    // Uttag
    public function withdraw(int $accountId, float $amount)
    {
        $account = $this->getAccountById($accountId);

        if (!$account || $account["balance"] < $amount) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE accounts
            SET balance = balance - ?
            WHERE id = ?
        ");
        return $stmt->execute([$amount, $accountId]);
    }

    // Överföring
    public function transfer(int $fromId, int $toId, float $amount)
    {
        $this->pdo->beginTransaction();

        $from = $this->getAccountById($fromId);
        $to = $this->getAccountById($toId);

        if (!$to) {
            $this->pdo->rollback();
            return false;
        }

        if (!$from || $from["balance"] < $amount) {
            $this->pdo->rollBack();
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE accounts
            SET balance = balance - ?
            WHERE id = ?
        ");
        $stmt->execute([$amount, $fromId]);

        $stmt = $this->pdo->prepare("
            UPDATE accounts
            SET balance = balance + ?
            WHERE id = ?
        ");
        $stmt->execute([$amount, $toId]);

        $this->pdo->commit();
        return true;
    }
}
