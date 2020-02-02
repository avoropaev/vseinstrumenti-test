<?php

declare(strict_types=1);

namespace App\Entity\Order\Item;

use App\Entity\Order\Order;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_items")
 */
class Item
{
    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="App\Entity\Order\Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private Order $order;

    /**
     * @var Id
     * @ORM\Column(type="order_item_id")
     * @ORM\Id
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
     * @param Order  $order
     * @param Id     $id
     * @param string $name
     * @param int    $price
     */
    public function __construct(Order $order, Id $id, string $name, int $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->order = $order;
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
