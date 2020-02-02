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

class AmountTest extends TestCase
{
    public function testSuccess(): void
    {
        $amount = 0;
        $order = (new OrderBuilder())->build();

        $this->assertEquals($amount, $order->amount());


        $product = (new ProductBuilder())->build();
        $amount += $product->price();
        $order->addItem(Id::next(), $product->name(), $product->price());
        $this->assertEquals($amount, $order->amount());


        $product = (new ProductBuilder())->withPrice(1000)->build();
        $amount += $product->price();
        $order->addItem(Id::next(), $product->name(), $product->price());
        $this->assertEquals($amount, $order->amount());
    }
}
