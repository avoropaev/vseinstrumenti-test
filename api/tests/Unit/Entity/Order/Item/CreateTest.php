<?php

declare(strict_types=1);

namespace Test\Unit\Entity\Order\Item;

use App\Entity\Order\Item\Id;
use App\Entity\Order\Item\Item;
use PHPUnit\Framework\TestCase;
use Test\Builder\Order\OrderBuilder;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $order = (new OrderBuilder())->build();

        $orderItem = new Item(
            $order,
            $id = Id::next(),
            $name = 'Item',
            $price = 100
        );

        $this->assertEquals($id, $orderItem->id());
        $this->assertEquals($name, $orderItem->name());
        $this->assertEquals($price, $orderItem->price());
    }
}
