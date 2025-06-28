<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>
    <div class="container">
        <h2>Inloggen</h2>

        <?php if (! empty($errors)) : ?>
            <?php foreach ($errors as $error) : ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post" action="/login">
            <input type="hidden" name="csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
            <label for="username">Gebruikersnaam:</label>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Wachtwoord:</label>
            <input type="password" name="password" id="password" required><br><br>

            <button type="submit">Login</button>
        </form>

        <p>Nog geen account? <a href="/registreren">Registreer hier</a></p>
    </div>
</body>

</html>