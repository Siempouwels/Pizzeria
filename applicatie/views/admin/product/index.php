<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productbeheer</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
    <link rel="stylesheet" href="/public/css/pagination.css">
</head>

<body>
    <?php include __DIR__ . '/../../layout/navbar.php'; ?>

    <div class="container">
        <h2>Productbeheer</h2>
        <p>
            <a href="/admin/producten/toevoegen">➕ Nieuw product toevoegen</a>
        </p>

        <?php if (empty($products)) : ?>
            <p>Er zijn nog geen producten beschikbaar.</p>
        <?php else : ?>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Prijs</th>
                        <th>Type</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?= htmlspecialchars($product['item_name'] ?? '') ?></td>
                            <td>€<?= number_format($product['price'] ?? 0, 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($product['type'] ?? '') ?></td>
                            <td>
                                <a href="/admin/producten/bewerken/<?= urlencode($product['item_name']) ?>" title="Bewerken">✏️</a>
                                <a href="/admin/producten/verwijderen/<?= urlencode($product['item_name']) ?>" title="Verwijderen">🗑️</a>
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