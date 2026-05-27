<?php $accounts = isset($accounts) ? $accounts : []; ?>

<h1>Överföring</h1>

<p>
    <a href="?page=dashboard">
        <= Tillbaka till Mina Sidor!</a>
</p>

<p>Välj från vilket konto du vill överföra pengar.</p>

<form method="POST" action="?page=transfer">

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

    <div>
        <label>Från konto</label>
        <select name="from_account_id" required>
            <?php foreach ($accounts as $acc): ?>
                <option value="<?= $acc["id"] ?>">
                    <?= htmlspecialchars($acc["account_type"]) ?> (Saldo: <?= $acc["balance"] ?> kr)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>

    <div>
        <label>Till konto</label>
        <select name="to_account_id" required>
            <?php foreach ($accounts as $acc): ?>
                <option value="<?= $acc["id"] ?>">
                    <?= htmlspecialchars($acc["account_type"]) ?> (Saldo: <?= $acc["balance"] ?> kr)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>

    <div>
        <label>Belopp</label>
        <input type="number" name="amount" step="0.01" min="0" required>
    </div>

    <br>

    <button type="submit">Överför</button>

</form>