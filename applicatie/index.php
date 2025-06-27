<?php

namespace Applicatie;

use App\Controllers\AdminOrderController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\OrderController;
use App\Controllers\OrderHistoryController;
use App\Controllers\ProductAdminController;
use App\Controllers\ProductController;
use App\Controllers\UserAdminController;
use Core\Auth;
use Core\Router;

Auth::ensureSession();
$router = new Router();

// Publieke routes
$router->get('/', fn () => (new ProductController())->index());
$router->post('/add-to-cart', fn () => (new ProductController())->addToCart());

$router->get('/login', fn () => (new AuthController())->loginForm());
$router->post('/login', fn () => (new AuthController())->handleLogin());
$router->get('/logout', fn () => (new AuthController())->logout());

$router->get('/registreren', fn () => (new AuthController())->registerForm());
$router->post('/registreren', fn () => (new AuthController())->handleRegistration());

$router->get('/nieuwe-bestelling', fn () => (new OrderController())->index());
$router->post('/nieuwe-bestelling', fn () => (new OrderController())->addProductsToCart());
$router->get('/winkelmandje', fn () => (new CartController())->index());
$router->post('/cart/update-item', fn () => (new CartController())->updateItem());
$router->post('/cart/clear', fn () => (new CartController())->clearCart());
$router->post('/cart/checkout', fn () => (new CartController())->checkout());
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
