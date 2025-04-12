<?php
require_once __DIR__.'/../../core/Auth.php';

$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
$isLoggedIn = Auth::isLoggedIn();
$isPersonnel = Auth::isPersonnel();

$user = Auth::user();
$firstName = htmlspecialchars($user['first_name'] ?? '');
$lastName = htmlspecialchars($user['last_name'] ?? '');
$role = htmlspecialchars($user['role'] ?? '');
?>

<nav class="main-navbar">
    <ul class="nav-left">
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

    <ul class="nav-right">
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