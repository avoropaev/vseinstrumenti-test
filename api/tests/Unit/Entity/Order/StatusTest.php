<?php

declare(strict_types=1);

namespace Test\Unit\Entity\Order;

use App\Entity\Order\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testCreate(): void
    {
        $status = new Status(Status::NEW);

        $this->assertEquals(Status::NEW, $status->name());
        $this->assertEquals(Status::NEW, $status);
        $this->assertTrue($status->isNew());


        $status = new Status(Status::PAID);

        $this->assertEquals(Status::PAID, $status->name());
        $this->assertEquals(Status::PAID, $status);
        $this->assertTrue($status->isPaid());

        $this->expectException(InvalidArgumentException::class);
        new Status('invalid');
    }

    public function testEqual(): void
    {
        $statusNew = Status::new();
        $statusPaid = Status::paid();

        $this->assertTrue($statusNew->isEqual($statusNew));
        $this->assertFalse($statusNew->isEqual($statusPaid));
    }
}
