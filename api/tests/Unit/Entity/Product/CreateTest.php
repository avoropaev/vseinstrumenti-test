<?php

declare(strict_types=1);

namespace Test\Unit\Entity\Product;

use App\Entity\Product\Id;
use App\Entity\Product\Product;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $product = new Product(
            $id = new Id(1),
            $name = 'test',
            $price = 100
        );

        $this->assertEquals($id, $product->id());
        $this->assertEquals($name, $product->name());
        $this->assertEquals($price, $product->price());
    }
}
