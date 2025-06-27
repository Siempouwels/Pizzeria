<?php

require_once __DIR__ . '/../models/Cart.php';

class CartController
{
    public function index(): void
    {
        $cart = new Cart();
        $productPrices = $cart->getProductPrices();

        $success = $_SESSION['success'] ?? false;
        $errors = $_SESSION['errors'] ?? [];

        // reset na tonen
        unset($_SESSION['success'], $_SESSION['errors']);

        $user = Auth::user();

        include __DIR__ . '/../views/cart/index.php';
    }

    public function updateItem(): void
    {
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $product => $qty) {
                $qty = max(0, intval($qty));
                if ($qty === 0) {
                    unset($_SESSION['cart'][$product]);
                } else {
                    $_SESSION['cart'][$product] = $qty;
                }
            }
        }

        header("Location: /winkelmandje");
        exit;
    }


    public function clearCart(): void
    {
        $cart = new Cart();
        $cart->clearCart();

        header("Location: /winkelmandje");
        exit;
    }

    public function checkout(): void
    {
        $cart = new Cart();

        $firstName = $_SESSION['first_name'] ?? $_POST['first_name'] ?? '';
        $lastName = $_SESSION['last_name'] ?? $_POST['last_name'] ?? '';
        $address = $_SESSION['address'] ?? $_POST['address'] ?? '';
        $username = $_SESSION['username'] ?? null;

        $result = $cart->checkout($firstName, $lastName, $address, $username);

        $_SESSION['success'] = $result['success'];
        $_SESSION['errors'] = $result['errors'];

        header("Location: /winkelmandje");
        exit;
    }
}
