<h1>Admin - Användare</h1>

<p>
    <a href="?page=dashboard">
        <= Tillbaka till Mina Sidor!</a>
</p>

<table border="1" cellpadding="5">

    <tr>
        <th>ID</th>
        <th>Namn</th>
        <th>Kortnummer</th>
        <th>Roll</th>
        <th>Skapad</th>
    </tr>

    <?php $users = isset($users) && is_array($users) ? $users : []; ?>
    <?php foreach ($users as $user): ?>

        <tr>

            <td>
                <?= htmlspecialchars($user["id"]) ?>
            </td>

            <td>
                <?= htmlspecialchars($user["name"]) ?>
            </td>

            <td>
                <?= htmlspecialchars($user["card_number"]) ?>
            </td>

            <td>
                <?= htmlspecialchars($user["role"]) ?>
            </td>

            <td>
                <?= htmlspecialchars($user["created_at"]) ?>
            </td>

        </tr>

    <?php endforeach; ?>

</table>