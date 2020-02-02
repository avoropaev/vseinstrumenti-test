<?php

declare(strict_types=1);

namespace App\Service\UseCase\Product\CreateRandom;

use App\Entity\Product\Product;
use App\Repository\ProductRepository;
use App\Service\Flusher;
use Webmozart\Assert\Assert;

class Handler
{
    private ProductRepository $productRepository;
    private Flusher $flusher;

    /**
     * Handler constructor.
     *
     * @param ProductRepository $productRepository
     * @param Flusher           $flusher
     */
    public function __construct(ProductRepository $productRepository, Flusher $flusher)
    {
        $this->productRepository = $productRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        Assert::notNull($command->count);

        $count = $command->count;

        do {
            $id = $this->productRepository->nextId();

            $product = new Product(
                $id,
                'Product ' . $id,
                rand(5, 5000)
            );

            $this->productRepository->add($product);

            $count--;
        } while ($count > 0);

        $this->flusher->flush();
    }
}
