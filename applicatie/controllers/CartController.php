<?php
require_once __DIR__.'/../models/Cart.php';

class CartController
{
    public function index(): void
    {
        $cart = new Cart();
        $result = $cart->handleActions();
        extract($result); // maakt $success, $errors, $productPrices beschikbaar

        include __DIR__.'/../views/cart/index.php';
    }
}
