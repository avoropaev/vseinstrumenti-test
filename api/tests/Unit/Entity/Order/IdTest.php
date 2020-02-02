<?php

declare(strict_types=1);

namespace Test\Unit\Entity\Order;

use App\Entity\Order\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    public function testCreateSuccess(): void
    {
        $orderId = new Id(
            $id = 1
        );

        $this->assertEquals($id, $orderId->value());
        $this->assertEquals(
            (string) $id,
            $orderId
        );
    }

    public function testCreateFailed(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Id(0);
    }
}
