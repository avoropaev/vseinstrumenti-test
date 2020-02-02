<?php

declare(strict_types=1);

namespace Test\Unit\Entity\Product;

use App\Entity\Product\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    public function testCreateSuccess(): void
    {
        $productId = new Id(
            $id = 1
        );

        $this->assertEquals($id, $productId->value());
        $this->assertEquals(
            (string) $id,
            $productId
        );
    }

    public function testCreateFailed(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Id(0);
    }
}
