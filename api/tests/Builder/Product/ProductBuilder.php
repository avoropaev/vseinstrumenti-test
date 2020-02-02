<?php

declare(strict_types=1);

namespace Test\Builder\Product;

use App\Entity\Product\Id;
use App\Entity\Product\Product;

class ProductBuilder
{
    private Id $id;
    private string $name;
    private int $price;

    /**
     * ProductBuilder constructor.
     */
    public function __construct()
    {
        $this->id = new Id(1);
        $this->name = 'Product';
        $this->price = 100;
    }

    /**
     * @param Id $id
     *
     * @return $this
     */
    public function withId(Id $id): self
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @param int $price
     *
     * @return $this
     */
    public function withPrice(int $price): self
    {
        $clone = clone $this;
        $clone->price = $price;

        return $clone;
    }

    /**
     * @return Product
     */
    public function build(): Product
    {
        return new Product(
            $this->id,
            $this->name,
            $this->price
        );
    }
}
