<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Gebruiker bewerken</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__ . '/../../layout/navbar.php'; ?>

    <div class="container">
        <h2>Gebruiker bewerken</h2>

        <form method="post" action="/admin/gebruikers/update">
            <input type="hidden" name="csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
            <label for="username">Gebruikersnaam:</label><br>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                readonly><br><br>

            <label for="first_name">Voornaam:</label><br>
            <input type="text" id="first_name" name="first_name"
                value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required><br><br>

            <label for="last_name">Achternaam:</label><br>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                required><br><br>

            <label for="address">Adres:</label><br>
            <input type="text" id="address" name="address"
                value="<?= htmlspecialchars($user['address'] ?? '') ?>"><br><br>

            <label for="role">Rol:</label><br>
            <select name="role" id="role" required>
                <option value="Customer" <?= ($user['role'] ?? '') === 'Customer' ? 'selected' : '' ?>>Customer</option>
                <option value="Personnel" <?= ($user['role'] ?? '') === 'Personnel' ? 'selected' : '' ?>>Personnel</option>
            </select><br><br>

            <button type="submit">Opslaan</button>
            <a href="/admin/gebruikers">Annuleren</a>
        </form>
    </div>
</body>

</html>