<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($ingredient) ? 'Ingrediënt bewerken' : 'Nieuw ingrediënt' ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__ . '/../../layout/navbar.php'; ?>

    <div class="container">
        <h2><?= isset($ingredient) ? 'Ingrediënt bewerken' : 'Nieuw ingrediënt toevoegen' ?></h2>

        <?php if (!empty($_SESSION['errors'])): ?>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach;
            unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="success"><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="post" action="<?= isset($ingredient) ? '/admin/ingredienten/update' : '/admin/ingredienten/opslaan' ?>">
            <input type="hidden" name="csrf_token" value="<?= \Core\Auth::csrfToken() ?>">

            <?php if (isset($ingredient)) : ?>
                <input type="hidden" name="original_name" value="<?= htmlspecialchars($ingredient['name'] ?? '') ?>">
            <?php endif; ?>

            <label for="name">Naam:</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($ingredient['name'] ?? '') ?>"
                required
                <?= isset($ingredient['is_used']) && $ingredient['is_used'] ? 'readonly' : '' ?>><br><br>

            <button type="submit"><?= isset($ingredient) ? 'Bijwerken' : 'Toevoegen' ?></button>
        </form>
    </div>
</body>

</html>