<?php

declare(strict_types=1);

namespace Test\Unit\Entity\Order\Item;

use App\Entity\Order\Item\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    public function testCreateSuccess(): void
    {
        $orderItemId = new Id(
            $id = '00000000-0000-0000-0000-000000000001'
        );

        $this->assertEquals($id, $orderItemId->value());
        $this->assertEquals($id, $orderItemId);
    }

    public function testCreateEmptyFailed(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Id('');
    }

    public function testCreateInvalidFailed(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Id('123');
    }

    public function testNextSuccess(): void
    {
        $id = Id::next();

        $this->assertInstanceOf(Id::class, $id);
    }

    public function testEqualSuccess(): void
    {
        $id1 = Id::next();
        $id2 = Id::next();

        $this->assertTrue($id1->isEqual($id1));
        $this->assertFalse($id1->isEqual($id2));
    }
}
