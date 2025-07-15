<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingrediënten beheren</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
    <link rel="stylesheet" href="/public/css/pagination.css">
</head>

<body>
    <?php include __DIR__ . '/../../layout/navbar.php'; ?>

    <div class="container">
        <h2>Ingrediënten</h2>

        <a href="/admin/ingredienten/nieuw" class="button">➕ Nieuw ingrediënt toevoegen</a>

        <?php if (empty($ingredients)): ?>
            <p>Er zijn nog geen ingrediënten toegevoegd.</p>
        <?php else: ?>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Status</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <tr>
                            <td><?= htmlspecialchars($ingredient['name']) ?></td>
                            <td>
                                <?php if ($ingredient['is_used']): ?>
                                    <span class="text-muted">In gebruik</span>
                                <?php else: ?>
                                    <span class="text-success">Vrij</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (! $ingredient['is_used']): ?>
                                    <a href="/admin/ingredienten/bewerken/<?= urlencode($ingredient['name']) ?>" title="Bewerken">✏️</a>
                                    <a href="/admin/ingredienten/verwijderen/<?= urlencode($ingredient['name']) ?>" title="Verwijderen">🗑️</a>
                                <?php else: ?>
                                    <span class="text-muted">(niet bewerkbaar)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
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