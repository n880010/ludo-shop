<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CartServiceTest extends TestCase
{
    private CartService $service;

    protected function setUp(): void
    {
        $em = $this->createStub(EntityManagerInterface::class);
        $this->service = new CartService($em);
    }

    public function testEmptyCartReturnsZero(): void
    {
        $user = $this->createStub(User::class);
        $cart = new Cart($user);

        $this->assertSame(0.0, $this->service->getTotal($cart));
    }

    public function testSingleItemReturnsCorrectTotal(): void
    {
        $user = $this->createStub(User::class);
        $cart = new Cart($user);

        $product = new Product();
        $product->setName('Catan');
        $product->setPrice(25.00);

        $item = new CartItem($product);
        $item->setQuantity(1);
        $item->setUnitPrice(25.00);
        $cart->addItem($item);

        $this->assertSame(25.00, $this->service->getTotal($cart));
    }

    // | à créer `testMultipleItems` | 2 produits différents | Somme des sous-totaux |
    public function testMultipleItems(): void
    {
        $user = $this->createStub(User::class);
        $cart = new Cart($user);

        $product1 = new Product();
        $product1->setName('Catan');
        $product1->setPrice(25.00);

        $item1 = new CartItem($product1);
        $item1->setQuantity(2); // Quantité de 2
        $item1->setUnitPrice(25.00);
        $cart->addItem($item1);

        $product2 = new Product();
        $product2->setName('Carcassonne');
        $product2->setPrice(30.00);

        $item2 = new CartItem($product2);
        $item2->setQuantity(1); // Quantité de 1
        $item2->setUnitPrice(30.00);
        $cart->addItem($item2);

        // Total attendu : (2 * 25) + (1 * 30) = 50 + 30 = 80
        $this->assertSame(80.00, $this->service->getTotal($cart));
    }

    // | à créer `testQuantityMultiplier` | 1 produit × 3 | Prix × 3 |
    public function testQuantityMultiplier(): void
    {
        $user = $this->createStub(User::class);
        $cart = new Cart($user);

        $product = new Product();
        $product->setName('Catan');
        $product->setPrice(25.00);

        $item = new CartItem($product);
        $item->setQuantity(3); // Quantité de 3
        $item->setUnitPrice(25.00);
        $cart->addItem($item);

        // Total attendu : 3 * 25 = 75
        $this->assertSame(75.00, $this->service->getTotal($cart));
    }
}
