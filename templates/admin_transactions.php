<h1>Admin - Transaktioner</h1>

<p>
    <a href="?page=dashboard">
        <= Tillbaka till Mina Sidor!</a>
</p>

<table border="1" cellpadding="5">

    <tr>
        <th>ID</th>
        <th>Typ</th>
        <th>Belopp</th>
        <th>Från konto</th>
        <th>Till konto</th>
        <th>Datum</th>
    </tr>

    <?php $transactions = isset($transactions) && is_array($transactions) ? $transactions : []; ?>
    <?php foreach ($transactions as $t): ?>

        <tr>
            <td><?= htmlspecialchars($t["id"]) ?></td>
            <td><?= htmlspecialchars($t["type"]) ?></td>
            <td><?= htmlspecialchars($t["amount"]) ?> kr</td>
            <td><?= htmlspecialchars($t["from_account_id"] ?? "-") ?></td>
            <td><?= htmlspecialchars($t["to_account_id"] ?? "-") ?></td>
            <td><?= htmlspecialchars($t["created_at"]) ?></td>
        </tr>

    <?php endforeach; ?>

</table>