<?php

declare(strict_types=1);

namespace App\Service\UseCase\Order\Create;

use App\Entity\Order\Id;
use App\Entity\Order\Order;
use App\Entity\Order\Status;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\Flusher;
use App\Entity\Order\Item\Id as ItemId;
use App\Entity\Product\Id as ProductId;

class Handler
{
    private ProductRepository $productRepository;
    private OrderRepository $orderRepository;
    private Flusher $flusher;

    /**
     * Handler constructor.
     *
     * @param ProductRepository $productRepository
     * @param OrderRepository   $orderRepository
     * @param Flusher           $flusher
     */
    public function __construct(
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        Flusher $flusher
    ) {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     *
     * @return Id
     */
    public function handle(Command $command): Id
    {
        $order = new Order(
            $this->orderRepository->nextId(),
            Status::new()
        );

        $productsIds = $command->productsIds;

        /** @var int $productId */
        foreach ($productsIds as $productId) {
            $product = $this->productRepository->get(new ProductId($productId));
            $order->addItem(ItemId::next(), $product->name(), $product->price());
        }

        $this->orderRepository->add($order);
        $this->flusher->flush();

        return $order->id();
    }
}
