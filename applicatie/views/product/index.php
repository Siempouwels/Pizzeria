<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producten</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
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
            <?php endforeach;
            unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (count($items) === 0) : ?>
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
                            value="<?= \Core\Auth::csrfToken() ?>"
                        >
                        <input
                            type="hidden"
                            name="item_name"
                            value="<?= htmlspecialchars($item['item_name']) ?>"
                        >
                        <label for="qty_<?= htmlspecialchars($item['item_name']) ?>">Aantal:</label>
                        <input
                            type="number"
                            name="quantity"
                            id="qty_<?= htmlspecialchars($item['item_name']) ?>"
                            value="0"
                            min="0"
                        >
                        <button type="submit">Toevoegen</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>
