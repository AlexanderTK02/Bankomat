<h1>Login</h1>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

    <div>
        <label>Kortnummer</label>
        <input type="text" name="card_number" required>
    </div>

    <div>
        <label>PIN</label>
        <input type="password" name="pin" required>
    </div>

    <button type="submit">Logga in</button>
</form>