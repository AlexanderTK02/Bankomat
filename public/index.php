<?php

session_start();

if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

require __DIR__ . "/../src/db.php";
require __DIR__ . "/../src/helpers.php";

require __DIR__ . "/../src/UserRepository.php";
require __DIR__ . "/../src/AccountRepository.php";
require __DIR__ . "/../src/TransactionRepository.php";

$userRepo = new UserRepository($pdo);
$accountRepo = new AccountRepository($pdo);
$transactionRepo = new TransactionRepository($pdo);

$page = $_GET["page"] ?? "login";

if ($page === "login" && $_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !isset($_POST["csrf_token"]) ||
        $_POST["csrf_token"] !== $_SESSION["csrf_token"]
    ) {
        die("CSRF_token ogiltig");
    }

    $cardNumber = trim($_POST["card_number"] ?? "");
    $pin = trim($_POST["pin"] ?? "");

    $user = $userRepo->findByCardNumber($cardNumber);

    if ($user && password_verify($pin, $user["pin_hash"])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["role"] = $user["role"];

        header("Location: ?page=dashboard");
        exit;
    }

    echo "Fel kortnummer eller PIN";
}

if ($page === "dashboard") {

    if (!isset($_SESSION["user_id"])) {
        header("Location: ?page=login");
        exit;
    }

    $accounts = $accountRepo->getAccountsByUser($_SESSION["user_id"]);

    $transactions = $transactionRepo->getByUser($_SESSION["user_id"]);

    require  __DIR__ . "/../templates/dashboard.php";
    exit;
}

if ($page === "admin_users") {

    require_role("admin");

    $users = $userRepo->getAllUsers();

    require  __DIR__ . "/../templates/admin_users.php";
    exit;
}

if ($page === "admin_accounts") {

    require_role("admin");

    $accounts = $accountRepo->getAllAccounts();

    require __DIR__ . "/../templates/admin_accounts.php";
    exit;
}

if ($page === "admin_transactions") {

    require_role("admin");

    $transactions = $transactionRepo->getAllTransactions();

    require __DIR__ . "/../templates/admin_transactions.php";
    exit;
}

if ($page === "deposit" && $_SERVER["REQUEST_METHOD"] === "GET") {

    if (!isset($_SESSION["user_id"])) {
        header("Location: ?page=login");
        exit;
    }

    $accounts = $accountRepo->getAccountsByUser($_SESSION["user_id"]);

    require __DIR__ . "/../templates/deposit.php";
    exit;
}


if ($page === "deposit" && $_SERVER["REQUEST_METHOD"] === "POST") {

    check_csrf();

    if (!isset($_SESSION["user_id"])) {
        header("Location: ?page=login");
        exit;
    }

    $amount = (float) ($_POST["amount"] ?? 0);
    $accountId = (int) ($_POST["account_id"] ?? 0);

    if ($amount <= 0) {
        echo "Ogiltigt belopp!";
        exit;
    }

    $account = $accountRepo->getAccountById($accountId);
    if (!$account || $account["user_id"] !== $_SESSION["user_id"]) {
        echo "Ogiltigt konto!";
        exit;
    }

    // Uppdatera saldo
    $accountRepo->deposit($accountId, $amount);

    // Logga transaktion
    $transactionRepo->log(null, $accountId, "deposit", $amount);

    header("Location: ?page=dashboard");
    exit;
}

if ($page === "withdraw" && $_SERVER["REQUEST_METHOD"] === "GET") {

    if (!isset($_SESSION["user_id"])) {
        header("Location: ?page=login");
        exit;
    }

    $accounts = $accountRepo->getAccountsByUser($_SESSION["user_id"]);

    require __DIR__ . "/../templates/withdraw.php";
    exit;
}


if ($page === "withdraw" && $_SERVER["REQUEST_METHOD"] === "POST") {

    check_csrf();

    if (!isset($_SESSION["user_id"])) {
        header("Location: ?page=login");
        exit;
    }

    $amount = (float) ($_POST["amount"] ?? 0);
    $accountId = (int) ($_POST["account_id"] ?? 0);

    if ($amount <= 0) {
        echo "Ogiltigt belopp!";
        exit;
    }

    $account = $accountRepo->getAccountById($accountId);
    if (!$account || $account["user_id"] !== $_SESSION["user_id"]) {
        echo "Ogiltigt konto!";
        exit;
    }

    // Försök göra uttag via repository
    $success = $accountRepo->withdraw($accountId, $amount);

    if (!$success) {
        echo "Otillräcklig saldo!";
        exit;
    }

    // Logga transaktionen
    $transactionRepo->log($accountId, null, "withdraw", $amount);

    header("Location: ?page=dashboard");
    exit;
}

if ($page === "transfer" && $_SERVER["REQUEST_METHOD"] === "GET") {

    if (!isset($_SESSION["user_id"])) {
        header("Location: ?page=login");
        exit;
    }

    // Hämta alla konton för användaren
    $accounts = $accountRepo->getAccountsByUser($_SESSION["user_id"]);

    require __DIR__ . "/../templates/transfer.php";
    exit;
}

if ($page === "transfer" && $_SERVER["REQUEST_METHOD"] === "POST") {

    check_csrf();

    if (!isset($_SESSION["user_id"])) {
        header("Location: ?page=login");
        exit;
    }

    $fromId = (int) ($_POST["from_account_id"] ?? 0);
    $toId = (int) ($_POST["to_account_id"] ?? 0);
    $amount = (float) ($_POST["amount"] ?? 0);

    if ($amount <= 0) {
        echo "Ogiltigt belopp!";
        exit;
    }

    if ($fromId === $toId) {
        echo "Du kan inte överföra till samma konto!";
        exit;
    }

    // Försök göra överföring
    $success = $accountRepo->transfer($fromId, $toId, $amount);

    if (!$success) {
        echo "Otillräcklig saldo!";
        exit;
    }

    // Logga transaktion
    $transactionRepo->log($fromId, $toId, "transfer", $amount);

    header("Location: ?page=dashboard");
    exit;
}

if ($page === "logout") {

    session_unset();
    session_destroy();

    header("Location: ?page=login");
    exit;
}

require  __DIR__ . "/../templates/login.php";
exit;
