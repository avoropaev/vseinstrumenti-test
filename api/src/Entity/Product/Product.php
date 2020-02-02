<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @var Id
     * @ORM\Column(type="product_id")
     * @ORM\Id
     * @ORM\SequenceGenerator(sequenceName="products_seq", initialValue=1)
     */
    private Id $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $price;

    /**
     * Product constructor.
     *
     * @param Id     $id
     * @param string $name
     * @param int    $price
     */
    public function __construct(Id $id, string $name, int $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function price(): int
    {
        return $this->price;
    }
}
