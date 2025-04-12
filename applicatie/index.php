<?php
session_start();

require_once __DIR__.'/core/Auth.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case '/':
    case '/index.php':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->index();
        break;

    case '/add-to-cart':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->addToCart(); // werkt via POST
        break;

    case '/login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        if ($method === 'POST') {
            $controller->handleLogin();
        } else {
            $controller->loginForm();
        }
        break;

    case '/logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case '/registreren':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        if ($method === 'POST') {
            $controller->handleRegistration();
        } else {
            $controller->registerForm();
        }
        break;

    case '/nieuwe-bestelling':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->addProductsView();
        break;

    case '/winkelmandje':
        require_once 'controllers/CartController.php';
        (new CartController())->index();
        break;

    case '/mijn-bestellingen':
        require_once 'controllers/OrderHistoryController.php';
        (new OrderHistoryController())->index();
        break;
    case '/admin/bestellingen':
        require_once 'controllers/AdminOrderController.php';
        Auth::requirePersonnel();
        (new AdminOrderController())->index();
        break;

    case '/admin/producten':
        require_once 'controllers/ProductAdminController.php';
        Auth::requirePersonnel();
        (new ProductAdminController())->index();
        break;

    case '/admin/gebruikers':
        require_once 'controllers/UserAdminController.php';
        Auth::requirePersonnel();
        (new UserAdminController())->index();
        break;

    // Product toevoegen
    case '/admin/producten/toevoegen':
        require_once 'controllers/ProductAdminController.php';
        Auth::requirePersonnel();
        (new ProductAdminController())->createForm();
        break;

    case '/admin/producten/create':
        require_once 'controllers/ProductAdminController.php';
        Auth::requirePersonnel();
        (new ProductAdminController())->store();
        break;

    // Product bewerken
    case '/admin/producten/bewerken':
        require_once 'controllers/ProductAdminController.php';
        Auth::requirePersonnel();
        (new ProductAdminController())->editForm();
        break;

    case '/admin/producten/update':
        require_once 'controllers/ProductAdminController.php';
        Auth::requirePersonnel();
        (new ProductAdminController())->update();
        break;

    // Verwijderen
    case '/admin/producten/verwijderen':
        require_once 'controllers/ProductAdminController.php';
        Auth::requirePersonnel();
        (new ProductAdminController())->delete();
        break;

    default:
        http_response_code(404);
        include 'views/errors/404.php'; // netjes aparte view
        break;
}
