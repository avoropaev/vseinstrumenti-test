<?php

declare(strict_types=1);

namespace Test\Unit\Entity\Order;

use App\Entity\Order\Id;
use App\Entity\Order\Order;
use App\Entity\Order\Status;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $order = new Order(
            $id = new Id(1),
            $status = Status::new()
        );

        $this->assertEquals($id, $order->id());
        $this->assertEquals($status, $order->status());
        $this->assertEmpty($order->items());
    }
}
