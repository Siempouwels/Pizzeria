<?php
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
$isLoggedIn = \Core\Auth::isLoggedIn();
$isPersonnel = \Core\Auth::isPersonnel();

$loggedInUser = \Core\Auth::user();
$firstName = htmlspecialchars($loggedInUser['first_name'] ?? '');
$lastName = htmlspecialchars($loggedInUser['last_name'] ?? '');
$role = htmlspecialchars($loggedInUser['role'] ?? '');
?>

<nav class="main-navbar">
    <!-- Checkbox voor togglen -->
    <input type="checkbox" id="menu-toggle" />
    <label for="menu-toggle" class="menu-icon">&#9776;</label>

    <ul class="nav-left menu">
        <li><a href="/">Home</a></li>

        <?php if ($isPersonnel) : ?>
            <li><a href="/admin/bestellingen">Bestellingen</a></li>
            <li><a href="/admin/producten">Producten</a></li>
            <li><a href="/admin/gebruikers">Gebruikers</a></li>
        <?php endif; ?>

        <li><a href="/nieuwe-bestelling">Bestellen</a></li>
        <li><a href="/mijn-bestellingen">Mijn bestellingen</a></li>
        <li><a href="/winkelmandje">Winkelmand (<?= $cartCount ?>)</a></li>
    </ul>

    <ul class="nav-right menu">
        <?php if ($isLoggedIn) : ?>
            <li>
                <span><?= "$firstName $lastName ($role)" ?></span>
            </li>
            <li><a href="/logout">Logout</a></li>
        <?php else : ?>
            <li><a href="/login">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
