<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Winkelmandje</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <div class="container">
        <h2>Winkelmandje</h2>

        <?php if (! empty($success)) : ?>
            <p class="success">✅ Bestelling succesvol geplaatst!</p>
        <?php endif; ?>

        <?php foreach ($errors as $error) : ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>

        <?php if (empty($_SESSION['cart'])) : ?>
            <p>Je winkelmandje is leeg.</p>
        <?php else : ?>
            <form method="post" action="/cart/update-item">
                <input type="hidden" name="csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Aantal</th>
                            <th>Prijs per stuk</th>
                            <th>Totaal</th>
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
                                    <input type="number" name="quantity[<?= htmlspecialchars($product) ?>]" value="<?= $qty ?>"
                                        min="0" required style="width: 60px;">
                                </td>
                                <td>€<?= number_format($prijs, 2, ',', '.') ?></td>
                                <td>€<?= number_format($sub, 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3"><strong>Totaal:</strong></td>
                            <td><strong>€<?= number_format($totaal, 2, ',', '.') ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <button type="submit">Winkelmandje bijwerken</button>
            </form>

            <form method="post" action="/cart/clear" style="margin-top: 20px;">
                <input type="hidden" name="csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
                <button type="submit" style="background-color: #d9534f;">Winkelmandje leegmaken</button>
            </form>

            <h3>Bestelling plaatsen</h3>
            <form method="post" action="/cart/checkout">
                <input type="hidden" name="csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
                <?php
                $isLoggedIn = Auth::isLoggedIn();
                $user = Auth::user();
                $firstName = $user['first_name'] ?? ($_POST['first_name'] ?? '');
                $lastName = $user['last_name'] ?? ($_POST['last_name'] ?? '');
                $address = $_POST['address'] ?? ($user['address'] ?? '');
                ?>

                <label for="first_name">Voornaam:</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($firstName) ?>"
                    <?= $isLoggedIn ? 'readonly' : 'required' ?>><br><br>

                <label for="last_name">Achternaam:</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($lastName) ?>" <?= $isLoggedIn ? 'readonly' : 'required' ?>><br><br>

                <label for="address">Adres:</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($address) ?>" required><br><br>

                <button type="submit">Bestelling plaatsen</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>