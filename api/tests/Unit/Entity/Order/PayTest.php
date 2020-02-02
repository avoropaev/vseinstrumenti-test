<?php

declare(strict_types=1);

namespace Test\Unit\Entity\Order;

use App\Entity\Order\Item\Id;
use App\Entity\Order\Order;
use App\Entity\Order\Status;
use Test\Builder\Order\OrderBuilder;
use DomainException;
use PHPUnit\Framework\TestCase;
use Test\Builder\Product\ProductBuilder;

class PayTest extends TestCase
{
    public function testSuccess(): void
    {
        $order = (new OrderBuilder())->build();
        $product = (new ProductBuilder())->build();
        $order->addItem(Id::next(), $product->name(), $product->price());

        $order->pay($product->price());

        $this->assertTrue($order->status()->isPaid());
    }

    public function testSmallAmount(): void
    {
        $order = (new OrderBuilder())->build();
        $product = (new ProductBuilder())->build();
        $order->addItem(Id::next(), $product->name(), $product->price());

        $this->expectException(DomainException::class);
        $order->pay($product->price() - 1);
    }

    public function testAlreadyPaid(): void
    {
        $order = (new OrderBuilder())->withStatus(Status::paid())->build();

        $this->expectException(DomainException::class);
        $order->pay(1);
    }
}
