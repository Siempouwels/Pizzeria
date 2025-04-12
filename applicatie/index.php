<?php
session_start();

require_once __DIR__.'/core/Auth.php';
require_once __DIR__.'/core/Router.php';
require_once __DIR__.'/controllers/ProductController.php';
require_once __DIR__.'/controllers/AuthController.php';
require_once __DIR__.'/controllers/OrderController.php';
require_once __DIR__.'/controllers/CartController.php';
require_once __DIR__.'/controllers/OrderHistoryController.php';
require_once __DIR__.'/controllers/AdminOrderController.php';
require_once __DIR__.'/controllers/UserAdminController.php';
require_once __DIR__.'/controllers/ProductAdminController.php';

$router = new Router();

// Publieke routes
$router->get('/', fn () => (new ProductController())->index());
$router->post('/add-to-cart', fn () => (new ProductController())->addToCart());

$router->get('/login', fn () => (new AuthController())->loginForm());
$router->post('/login', fn () => (new AuthController())->handleLogin());
$router->get('/logout', fn () => (new AuthController())->logout());

$router->get('/registreren', fn () => (new AuthController())->registerForm());
$router->post('/registreren', fn () => (new AuthController())->handleRegistration());

$router->get('/nieuwe-bestelling', fn () => (new OrderController())->addProductsView());
$router->get('/winkelmandje', fn () => (new CartController())->index());
$router->get('/mijn-bestellingen', fn () => (new OrderHistoryController())->index());

// Admin-only routes
$router->get('/admin/bestellingen', function () {
    Auth::requirePersonnel();
    (new AdminOrderController())->index();
});

$router->get('/admin/gebruikers', function () {
    Auth::requirePersonnel();
    (new UserAdminController())->index();
});

$router->get('/admin/gebruikers/bewerken/{username}', function ($username) {
    Auth::requirePersonnel();
    (new UserAdminController())->editForm(urldecode($username));
});

$router->post('/admin/gebruikers/update', function () {
    Auth::requirePersonnel();
    (new UserAdminController())->update();
});

$router->get('/admin/gebruikers/verwijderen/{username}', function ($username) {
    Auth::requirePersonnel();
    (new UserAdminController())->delete(urldecode($username));
});

$router->get('/admin/producten', function () {
    Auth::requirePersonnel();
    (new ProductAdminController())->index();
});

$router->get('/admin/producten/toevoegen', function () {
    Auth::requirePersonnel();
    (new ProductAdminController())->createForm();
});

$router->post('/admin/producten/create', function () {
    Auth::requirePersonnel();
    (new ProductAdminController())->store();
});

$router->get('/admin/producten/bewerken/{name}', function ($name) {
    Auth::requirePersonnel();
    (new ProductAdminController())->editForm(urldecode($name));
});

$router->post('/admin/producten/update', function () {
    Auth::requirePersonnel();
    (new ProductAdminController())->update();
});

$router->get('/admin/producten/verwijderen/{name}', function ($name) {
    Auth::requirePersonnel();
    (new ProductAdminController())->delete(urldecode($name));
});

$router->dispatch();