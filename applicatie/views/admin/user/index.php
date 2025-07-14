<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikersbeheer</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
    <link rel="stylesheet" href="/public/css/pagination.css">
</head>

<body>
    <?php include __DIR__ . '/../../layout/navbar.php'; ?>

    <div class="container">
        <h2>Gebruikersbeheer</h2>

        <?php if (empty($users)) : ?>
            <p>Er zijn nog geen geregistreerde gebruikers.</p>
        <?php else : ?>
            <table class="user-table">
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
                            <td>
                                <?= htmlspecialchars($user['first_name'] ?? '') ?>
                                <?= htmlspecialchars($user['last_name'] ?? '') ?>
                            </td>
                            <td><?= htmlspecialchars($user['address'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['role'] ?? '') ?></td>
                            <td class="actions">
                                <a href="/admin/gebruikers/bewerken/<?= $username ?>" title="Bewerken">✏️</a>
                                <a
                                    href="/admin/gebruikers/verwijderen/<?= $username ?>"
                                    title="Verwijderen"
                                    onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');"
                                >🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <nav class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="prev">« Vorige</a>
                <?php else: ?>
                    <span class="disabled">« Vorige</span>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <?php if ($p === $page): ?>
                        <span class="current"><?= $p ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $p ?>"><?= $p ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="next">Volgende »</a>
                <?php else: ?>
                    <span class="disabled">Volgende »</span>
                <?php endif; ?>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>
