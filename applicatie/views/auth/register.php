<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Registreren</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>
    <div class="container">
        <h2>Registreren</h2>

        <?php if (! empty($errors)) : ?>
            <?php foreach ($errors as $error) : ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post" action="/registreren">

            <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">
            <label for="username">Gebruikersnaam:</label>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Wachtwoord:</label>
            <input type="password" name="password" id="password" required><br><br>

            <label for="confirm_password">Bevestig wachtwoord:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br><br>

            <label for="first_name">Voornaam:</label>
            <input type="text" name="first_name" id="first_name" required><br><br>

            <label for="last_name">Achternaam:</label>
            <input type="text" name="last_name" id="last_name" required><br><br>

            <label for="address">Adres:</label>
            <input type="text" name="address" id="address"><br><br>

            <button type="submit">Registreer</button>
        </form>

        <p>Al een account? <a href="/login.php">Log hier in</a></p>
    </div>
</body>

</html>