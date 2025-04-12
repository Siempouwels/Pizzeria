<?php
Auth::ensureSession();
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Gebruikersbeheer</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__.'/../layout/navbar.php'; ?>

    <div class="container">
        <h2>Gebruikersbeheer</h2>

        <?php if (empty($users)) : ?>
            <p>Er zijn nog geen geregistreerde gebruikers.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Gebruikersnaam</th>
                        <th>Naam</th>
                        <th>Adres</th>
                        <th>Rol</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <?php $username = urlencode($user['username'] ?? ''); ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['first_name'] ?? '') ?>
                                <?= htmlspecialchars($user['last_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['address'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['role'] ?? '') ?></td>
                            <td>
                                <a href="/admin/gebruikers/bewerken/<?= $username ?>">✏️</a>
                                <a href="/admin/gebruikers/verwijderen/<?= $username ?>"
                                    onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>