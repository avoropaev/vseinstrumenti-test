<?php

declare(strict_types=1);

namespace Test\Builder\Order;

use App\Entity\Order\Id;
use App\Entity\Order\Order;
use App\Entity\Order\Status;

class OrderBuilder
{
    private Id $id;
    private Status $status;

    /**
     * OrderBuilder constructor.
     */
    public function __construct()
    {
        $this->id = new Id(1);
        $this->status = Status::new();
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
     * @param Status $status
     *
     * @return $this
     */
    public function withStatus(Status $status): self
    {
        $clone = clone $this;
        $clone->status = $status;

        return $clone;
    }

    /**
     * @return Order
     */
    public function build(): Order
    {
        return new Order(
            $this->id,
            $this->status
        );
    }
}
