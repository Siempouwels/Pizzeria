<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn bestellingen</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
    <link rel="stylesheet" href="/public/css/orders.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <div class="container">
        <h2>Mijn bestellingen</h2>

        <?php if (empty($orders)) : ?>
            <p>Je hebt nog geen bestellingen geplaatst.</p>
        <?php else : ?>
            <?php foreach ($orders as $order) : ?>
                <div class="order">
                    <h3>
                        Bestelling #<?= htmlspecialchars((string) $order['order_id']) ?>
                        (<?= date('d-m-Y H:i', strtotime($order['datetime'])) ?>)
                    </h3>

                    <p>
                        <strong>Status:</strong>
                        <?= isset($order['status'])
                            ? htmlspecialchars($order['status'])
                            : '' ?>
                    </p>

                    <p>
                        <strong>Naam:</strong>
                        <?= isset($order['client_name'])
                            ? htmlspecialchars($order['client_name'])
                            : '' ?>
                    </p>

                    <p>
                        <strong>Adres:</strong>
                        <?= isset($order['address'])
                            ? htmlspecialchars($order['address'])
                            : '' ?>
                    </p>

                    <?php if (! empty($orderItems[$order['order_id']])) : ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Aantal</th>
                                    <th>Prijs</th>
                                    <th>Totaal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $subtotaal = 0;
                                foreach ($orderItems[$order['order_id']] as $item) :
                                    $totaal = $item['quantity'] * $item['price'];
                                    $subtotaal += $totaal;
                                    ?>
                                    <tr>
                                        <td>
                                            <?= isset($item['item_name'])
                                                ? htmlspecialchars($item['item_name'])
                                                : '' ?>
                                        </td>
                                        <td><?= (int) $item['quantity'] ?></td>
                                        <td>€<?= number_format((float) $item['price'], 2, ',', '.') ?></td>
                                        <td>€<?= number_format((float) $totaal, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="3" style="text-align: right;">
                                        <strong>Subtotaal:</strong>
                                    </td>
                                    <td>
                                        <strong>€<?= number_format($subtotaal, 2, ',', '.') ?></strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p><em>Geen producten gevonden voor deze bestelling.</em></p>
                    <?php endif; ?>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>
