<?php require_once __DIR__.'/../../core/Auth.php';
Auth::ensureSession(); ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Productbeheer</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__.'/../layout/navbar.php'; ?>

    <div class="container">
        <h2>Productbeheer</h2>
        <p><a href="/admin/producten/toevoegen">➕ Nieuw product toevoegen</a></p>

        <?php if (empty($products)) : ?>
            <p>Er zijn nog geen producten beschikbaar.</p>
        <?php else : ?>
            <table>
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
                                <a href="/admin/producten/bewerken/<?= urlencode($product['item_name']) ?>">✏️</a>
                                <a href="/admin/producten/verwijderen/<?= urlencode($product['item_name']) ?>"
                                    onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">🗑️
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>