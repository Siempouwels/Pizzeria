<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Nieuwe bestelling</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__.'/../layout/navbar.php'; ?>

    <div class="container">
        <h2>Producten toevoegen aan winkelmandje</h2>

        <?php if ($success) : ?>
            <p class="success">✅ Product(en) toegevoegd aan winkelmandje!</p>
        <?php endif; ?>

        <?php foreach ($errors as $error) : ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>

        <form method="post" class="order">
            <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Prijs</th>
                        <th>Hoeveelheid</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item) : ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>€<?= number_format($item['price'], 2, ',', '.') ?></td>
                            <td>
                                <input type="number" name="aantal[<?= htmlspecialchars($item['name']) ?>]" value="0"
                                    min="0">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <button type="submit">Toevoegen aan winkelmandje</button>
        </form>
    </div>
</body>

</html>