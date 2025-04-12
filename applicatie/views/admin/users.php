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
                        <tr>
                            <td><?= htmlspecialchars($user['username'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['first_name'] ?? '') ?>
                                <?= htmlspecialchars($user['last_name'] ?? '') ?>
                            </td>
                            <td><?= htmlspecialchars($user['address'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['role'] ?? '') ?></td>
                            <td>
                                <!-- Toekomstige acties zoals aanpassen/verwijderen -->
                                <a href="#">✏️</a>
                                <a href="#">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>