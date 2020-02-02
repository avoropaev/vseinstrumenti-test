<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Order\Item\Item;
use App\Entity\Order\Item\Id as ItemId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @var Id
     * @ORM\Column(type="order_id")
     * @ORM\Id
     * @ORM\SequenceGenerator(sequenceName="orders_seq", initialValue=1)
     */
    private Id $id;

    /**
     * @var Status
     * @ORM\Column(type="order_status", length=16)
     */
    private Status $status;

    /**
     * @var Collection
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Order\Item\Item",
     *     mappedBy="order",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private Collection $items;

    /**
     * Order constructor.
     *
     * @param Id     $id
     * @param Status $status
     */
    public function __construct(Id $id, Status $status)
    {
        $this->id = $id;
        $this->status = $status;
        $this->items = new ArrayCollection();
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return Status
     */
    public function status(): Status
    {
        return $this->status;
    }

    /**
     * @return Item[]
     */
    public function items(): array
    {
        /** @var Item[] $items */
        $items = $this->items->toArray();

        return $items;
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        $amount = 0;

        /** @var Item $item */
        foreach ($this->items as $item) {
            $amount += $item->price();
        }

        return $amount;
    }

    /**
     * @param ItemId $id
     * @param string $name
     * @param int    $price
     *
     * @return Order
     */
    public function addItem(ItemId $id, string $name, int $price): self
    {
        $this->items->add(new Item($this, $id, $name, $price));

        return $this;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function pay(int $amount): self
    {
        if ($this->status->isEqual(Status::paid())) {
            throw new DomainException('Order already paid.');
        }

        if ($this->amount() > $amount) {
            throw new DomainException('The order amount is ' . ($this->amount() - $amount) . ' more than ' .
                'the transferred amount.');
        }

        $this->status = Status::paid();

        return $this;
    }
}
