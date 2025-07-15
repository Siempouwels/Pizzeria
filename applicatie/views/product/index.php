<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producten</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
    <link rel="stylesheet" href="/public/css/pagination.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <div class="container">
        <h2>Producten</h2>

        <?php if (! empty($_SESSION['success'])) : ?>
            <p class="success">✅ Product toegevoegd aan winkelmandje.</p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (! empty($_SESSION['errors'])) : ?>
            <?php foreach ($_SESSION['errors'] as $error) : ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (empty($items)) : ?>
            <p>Er zijn nog geen producten beschikbaar.</p>
        <?php else : ?>
            <?php foreach ($items as $item) : ?>
                <div class="item">
                    <h3><?= htmlspecialchars($item['item_name']) ?></h3>
                    <p><strong>Type:</strong> <?= htmlspecialchars($item['type']) ?></p>
                    <p><strong>Prijs:</strong> €<?= number_format($item['price'], 2, ',', '.') ?></p>

                    <?php if (! empty($ingredients[$item['item_name']])) : ?>
                        <p>
                            <strong>Ingrediënten:</strong>
                            <?= htmlspecialchars(implode(', ', $ingredients[$item['item_name']])) ?>
                        </p>
                    <?php else : ?>
                        <p><em>Geen ingrediënten bekend</em></p>
                    <?php endif; ?>

                    <form method="post" action="/add-to-cart">
                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= \Core\Auth::csrfToken() ?>">
                        <input
                            type="hidden"
                            name="item_name"
                            value="<?= htmlspecialchars($item['item_name']) ?>">
                        <?php
                        $sanitizedId = 'qty_' . preg_replace(
                            '/[^a-zA-Z0-9_-]/',
                            '_',
                            $item['item_name']
                        );
                        ?>
                        <label for="<?= $sanitizedId ?>">Aantal:</label>
                        <input
                            type="number"
                            name="quantity"
                            id="<?= $sanitizedId ?>"
                            value="0"
                            min="0">
                        <button type="submit">Toevoegen</button>
                    </form>
                </div>
            <?php endforeach; ?>

            <?php if (isset($totalPages) && $totalPages > 1) : ?>
                <nav class="pagination">
                    <?php if ($page > 1) : ?>
                        <a href="?page=<?= $page - 1 ?>" class="prev">« Vorige</a>
                    <?php else : ?>
                        <span class="disabled">« Vorige</span>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $totalPages; $p++) : ?>
                        <?php if ($p === $page) : ?>
                            <span class="current"><?= $p ?></span>
                        <?php else : ?>
                            <a href="?page=<?= $p ?>"><?= $p ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages) : ?>
                        <a href="?page=<?= $page + 1 ?>" class="next">Volgende »</a>
                    <?php else : ?>
                        <span class="disabled">Volgende »</span>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>
