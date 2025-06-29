<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingrediënten beheren</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
</head>

<body>
    <?php include __DIR__ . '/../../layout/navbar.php'; ?>

    <div class="container">
        <h2>Ingrediënten</h2>

        <a href="/admin/ingredienten/nieuw">➕ Nieuw ingrediënt toevoegen</a>

        <?php if (count($ingredients) === 0) : ?>
            <p>Er zijn nog geen ingrediënten toegevoegd.</p>
        <?php else : ?>
            <ul>
                <?php foreach ($ingredients as $ingredient) : ?>
                    <li>
                        <?= htmlspecialchars($ingredient['name']) ?>
                        <?php if (! $ingredient['is_used']) : ?>
                            <a href="/admin/ingredienten/bewerken/<?= urlencode($ingredient['name']) ?>">✏️</a>
                            <a href="/admin/ingredienten/verwijderen/<?= urlencode($ingredient['name']) ?>">🗑️</a>
                        <?php else : ?>
                            <span style="color: gray;">(gebruikt in producten)</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>

</html>