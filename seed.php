<?php

require "src/db.php";

// Rensa tabeller
$pdo->exec("DELETE FROM transactions");
$pdo->exec("DELETE FROM accounts");
$pdo->exec("DELETE FROM users");

$pin = password_hash("1234", PASSWORD_DEFAULT);

// --------------------------------------
// Funktion för att skapa användare
// --------------------------------------
function createUser($pdo, $card, $pin, $name, $role)
{
    $stmt = $pdo->prepare("
        INSERT INTO users (card_number, pin_hash, name, role)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$card, $pin, $name, $role]);
    return $pdo->lastInsertId();
}

// Funktion för att skapa konto
function createAccount($pdo, $userId, $balance, $type = "checking")
{
    $stmt = $pdo->prepare("
        INSERT INTO accounts (user_id, balance, account_type)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$userId, $balance, $type]);
    return $pdo->lastInsertId();
}

// Funktion för att skapa transaktion
function createTransaction($pdo, $from, $to, $type, $amount)
{
    $stmt = $pdo->prepare("
        INSERT INTO transactions (from_account_id, to_account_id, type, amount)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$from, $to, $type, $amount]);
}

// --------------------------------------
// Skapa användare + konton
// --------------------------------------
$adminId = createUser($pdo, "123456789", $pin, "Alexander Admin", "admin");
$adminAcc = createAccount($pdo, $adminId, 5000, "checking");

// Kalle – två konton
$user1Id = createUser($pdo, "987654321", $pin, "Kalle Karlsson", "user");
$kalleChecking = createAccount($pdo, $user1Id, 2500, "checking");
$kalleSavings  = createAccount($pdo, $user1Id, 8000, "savings");

// Lisa – två konton
$user2Id = createUser($pdo, "555555555", $pin, "Lisa Larsson", "user");
$lisaChecking = createAccount($pdo, $user2Id, 10000, "checking");
$lisaSavings  = createAccount($pdo, $user2Id, 50000, "savings");

// Oskar – ett konto
$user3Id = createUser($pdo, "111222333", $pin, "Oskar Olsson", "user");
$oskarChecking = createAccount($pdo, $user3Id, 750, "checking");

// --------------------------------------
// Skapa transaktioner
// --------------------------------------
createTransaction($pdo, null, $adminAcc, "deposit", 500);
createTransaction($pdo, $adminAcc, null, "withdraw", 200);

// Kalle
createTransaction($pdo, null, $kalleChecking, "deposit", 300);
createTransaction($pdo, $kalleChecking, null, "withdraw", 150);

// Överföring: Kalle checking → Kalle savings
createTransaction($pdo, $kalleChecking, $kalleSavings, "transfer", 200);

// Lisa
createTransaction($pdo, null, $lisaChecking, "deposit", 2000);
createTransaction($pdo, $lisaChecking, null, "withdraw", 500);

// Överföring: Lisa checking → Lisa savings
createTransaction($pdo, $lisaChecking, $lisaSavings, "transfer", 300);

// Oskar
createTransaction($pdo, null, $oskarChecking, "deposit", 100);
createTransaction($pdo, $oskarChecking, null, "withdraw", 50);

echo "Seed klar!";
