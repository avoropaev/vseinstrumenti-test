<?php

declare(strict_types=1);

namespace App\ReadModel\Order;

class FullOrderView
{
    public ?int $id = null;
    public ?string $status = null;
    public array $items = [];

    /**
     * @return FullOrderItemView[]|array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param FullOrderItemView $item
     */
    public function addItem(FullOrderItemView $item): void
    {
        if (!in_array($item, $this->items, true)) {
            $this->items[] = $item;
        }
    }

    /**
     * @param FullOrderItemView $item
     */
    public function removeItem(FullOrderItemView $item): void
    {
        if (in_array($item, $this->items, true)) {
            $key = array_search($item, $this->items, true);

            if ($key !== false) {
                unset($this->items[$key]);
            }
        }
    }
}
