<h1>Admin - Konton</h1>

<p>
    <a href="?page=dashboard">
        <= Tillbaka till Mina Sidor!</a>
</p>

<table border="1" cellpadding="5">

    <tr>
        <th>ID</th>
        <th>Ägare</th>
        <th>Kontotyp</th>
        <th>Saldo</th>
    </tr>

    <?php $accounts = isset($accounts) && is_array($accounts) ? $accounts : []; ?>
    <?php foreach ($accounts as $acc): ?>

        <tr>
            <td><?= htmlspecialchars($acc["id"]) ?></td>
            <td><?= htmlspecialchars($acc["name"]) ?></td>
            <td><?= htmlspecialchars($acc["account_type"] ?? "Standard") ?></td>
            <td><?= htmlspecialchars($acc["balance"]) ?> kr</td>
        </tr>

    <?php endforeach; ?>

</table>