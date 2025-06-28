<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle bestellingen</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__ . '/../../layout/navbar.php'; ?>

    <div class="container">
        <h2>Alle klantbestellingen</h2>

        <?php if (empty($orders)) : ?>
            <p>Er zijn nog geen bestellingen geplaatst.</p>
        <?php else : ?>
            <?php foreach ($orders as $order) : ?>
                <div class="order">
                    <h3>
                        Bestelling #<?= htmlspecialchars($order['order_id'] ?? '?') ?>
                        (
                            <?= isset($order['datetime'])
                                ? date('d-m-Y H:i', strtotime($order['datetime']))
                                : 'onbekend' ?>
                        )
                    </h3>

                    <p>
                        <strong>Klant:</strong>
                        <?= htmlspecialchars($order['client_name'] ?? '') ?>
                        (<?= htmlspecialchars($order['client_username'] ?? '') ?>)
                    </p>

                    <p><strong>Adres:</strong> <?= htmlspecialchars($order['address'] ?? '') ?></p>

                    <p><strong>Status:</strong>
                        <form method="post" action="/admin/bestellingen/update-status" class="inline-form">
                            <input type="hidden" name="csrf_token" value="<?= \Core\Auth::csrfToken() ?>">

                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="1" <?= $order['status'] == 1 ? 'selected' : '' ?>>In afwachting</option>
                                <option value="2" <?= $order['status'] == 2 ? 'selected' : '' ?>>In voorbereiding</option>
                                <option value="3" <?= $order['status'] == 3 ? 'selected' : '' ?>>Afgeleverd</option>
                            </select>
                            <noscript><button type="submit">Wijzig</button></noscript>
                        </form>
                    </p>

                    <p><strong>Behandeld door:</strong> <?= htmlspecialchars($order['personnel_username'] ?? '-') ?></p>

                    <?php if (! empty($orderItems[$order['order_id']])) : ?>
                        <ul>
                            <?php foreach ($orderItems[$order['order_id']] as $item) : ?>
                                <li>
                                    <?= htmlspecialchars($item['item_name'] ?? '') ?> -
                                    <?= intval($item['quantity'] ?? 0) ?> stuks @ €
                                    <?= number_format($item['price'] ?? 0, 2, ',', '.') ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p><em>Geen producten gevonden</em></p>
                    <?php endif; ?>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>
