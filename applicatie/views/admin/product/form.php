<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($product) ? 'Product bewerken' : 'Nieuw product toevoegen' ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__ . '/../../layout/navbar.php'; ?>

    <div class="container">
        <h2><?= isset($product) ? 'Product bewerken' : 'Nieuw product toevoegen' ?></h2>

        <!-- Meldingen -->
        <?php if (!empty($_SESSION['errors'])) : ?>
            <?php foreach ($_SESSION['errors'] as $error) : ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach;
            unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])) : ?>
            <p class="success"><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Formulier -->
        <form method="post" action="<?= isset($product) ? '/admin/producten/update' : '/admin/producten/create' ?>">
            <input type="hidden" name="csrf_token" value="<?= \Core\Auth::csrfToken() ?>">

            <label for="name">Naam:</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($product['name'] ?? '') ?>"
                required
                <?= isset($product) ? 'readonly' : '' ?>><br><br>

            <label for="price">Prijs:</label><br>
            <input
                type="number"
                step="0.01"
                id="price"
                name="price"
                value="<?= htmlspecialchars($product['price'] ?? '') ?>"
                required><br><br>

            <label for="type">Type:</label><br>
            <select id="type" name="type" required>
                <option value="" disabled <?= !isset($product['type_id']) ? 'selected' : '' ?>>-- Kies een type --</option>
                <?php foreach ($types as $typeOption) : ?>
                    <?php
                    $selected = ($product['type_id'] ?? '') === $typeOption['name'] ? 'selected' : '';
                    ?>
                    <option value="<?= htmlspecialchars($typeOption['name']) ?>" <?= $selected ?>>
                        <?= htmlspecialchars($typeOption['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <fieldset>
                <legend>Ingrediënten:</legend>
                <?php foreach ($allIngredients as $ingredient) : ?>
                    <?php
                    $name = $ingredient['name'];
                    $checked = isset($selectedIngredients) && in_array($name, $selectedIngredients) ? 'checked' : '';
                    ?>
                    <label>
                        <input type="checkbox" name="ingredients[]" value="<?= htmlspecialchars($name) ?>" <?= $checked ?>>
                        <?= htmlspecialchars($name) ?>
                    </label><br>
                <?php endforeach; ?>
            </fieldset><br>

            <button type="submit">Opslaan</button>
            <a href="/admin/producten">Annuleren</a>
        </form>
    </div>
</body>

</html>