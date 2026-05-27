<?php
if (!isset($accounts)) $accounts = [];
if (!isset($transactions) || !is_array($transactions)) $transactions = [];
?>

<h1>Mina Sidor</h1>

<p>Hej <?= htmlspecialchars($_SESSION["name"]) ?></p>

<?php if ($_SESSION["role"] === "admin"): ?>
    <p>
        <a href="?page=admin_users">Användare</a><br>
        <a href="?page=admin_accounts">Konton</a><br>
        <a href="?page=admin_transactions">Transaktioner</a>
    </p>
<?php endif; ?>

<h2>Dina konton</h2>

<?php foreach ($accounts as $acc): ?>
    <p>
        <?= htmlspecialchars($acc["account_type"]) ?>:
        <?= htmlspecialchars($acc["balance"]) ?> kr
    </p>
<?php endforeach; ?>

<h1>Insättning</h1>

<form method="POST" action="?page=deposit">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

    <select name="account_id" required>
        <?php foreach ($accounts as $acc): ?>
            <option value="<?= $acc["id"] ?>">
                <?= htmlspecialchars($acc["account_type"]) ?> (Saldo: <?= $acc["balance"] ?> kr)
            </option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="amount" placeholder="belopp" step="0.01" min="0" required>
    <button type="submit">Sätt in</button>
</form>

<h1>Uttag</h1>

<form method="POST" action="?page=withdraw">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

    <select name="account_id" required>
        <?php foreach ($accounts as $acc): ?>
            <option value="<?= $acc["id"] ?>">
                <?= htmlspecialchars($acc["account_type"]) ?> (Saldo: <?= $acc["balance"] ?> kr)
            </option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="amount" placeholder="belopp" step="0.01" min="0" required>
    <button type="submit">Ta ut</button>
</form>

<p><a href="?page=transfer">Överför pengar</a></p>

<h2>Senaste transaktionerna</h2>

<?php if (empty($transactions)): ?>
    <p>Inga transaktioner ännu.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>Typ</th>
            <th>Från konto</th>
            <th>Till konto</th>
            <th>Belopp</th>
            <th>Datum</th>
        </tr>

        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t["type"]) ?></td>
                <td><?= $t["from_account_id"] ?? "" ?></td>
                <td><?= $t["to_account_id"] ?? "" ?></td>
                <td><?= $t["amount"] ?> kr</td>
                <td><?= $t["created_at"] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<br>

<form method="GET" action="">
    <input type="hidden" name="page" value="logout">
    <button type="submit">Logga ut</button>
</form>