<?php if (session_status() === PHP_SESSION_NONE)
    session_start(); ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Winkelmandje</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__.'/../layout/navbar.php'; ?>

    <div class="container">
        <h2>Winkelmandje</h2>

        <?php if ($success) : ?>
            <p class="success">✅ Bestelling succesvol geplaatst!</p>
        <?php endif; ?>

        <?php foreach ($errors as $error) : ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>

        <?php if (empty($_SESSION['cart'])) : ?>
            <p>Je winkelmandje is leeg.</p>
        <?php else : ?>
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Aantal</th>
                            <th>Prijs per stuk</th>
                            <th>Totaal</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totaal = 0;
                        foreach ($_SESSION['cart'] as $product => $qty) :
                            $prijs = $productPrices[$product] ?? 0;
                            $sub = $prijs * $qty;
                            $totaal += $sub;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($product) ?></td>
                                <td>
                                    <input type="number" name="quantity" value="<?= $qty ?>" min="0">
                                    <input type="hidden" name="product" value="<?= htmlspecialchars($product) ?>">
                                </td>
                                <td>€<?= number_format($prijs, 2, ',', '.') ?></td>
                                <td>€<?= number_format($sub, 2, ',', '.') ?></td>
                                <td>
                                    <button type="submit" name="update_item">Bijwerken</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3"><strong>Totaal:</strong></td>
                            <td><strong>€<?= number_format($totaal, 2, ',', '.') ?></strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <form method="post" style="margin-top: 20px;">
                <button type="submit" name="clear_cart" style="background-color: #d9534f;">Winkelmandje leegmaken</button>
            </form>

            <h3>Bestelling plaatsen</h3>
            <form method="post">
                <?php if (! isset($_SESSION['username'])) : ?>
                    <label for="first_name">Voornaam:</label>
                    <input type="text" name="first_name" id="first_name" required><br><br>

                    <label for="last_name">Achternaam:</label>
                    <input type="text" name="last_name" id="last_name" required><br><br>

                    <label for="address">Adres:</label>
                    <input type="text" name="address" id="address" required><br><br>
                <?php endif; ?>

                <button type="submit" name="confirm_order">Bestelling plaatsen</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>