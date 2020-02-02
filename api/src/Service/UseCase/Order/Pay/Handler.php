<?php

declare(strict_types=1);

namespace App\Service\UseCase\Order\Pay;

use App\Entity\Order\Id;
use App\Repository\OrderRepository;
use App\Service\Flusher;
use DomainException;
use GuzzleHttp\Client;
use Webmozart\Assert\Assert;

class Handler
{
    private OrderRepository $orderRepository;
    private Flusher $flusher;

    /**
     * Handler constructor.
     *
     * @param OrderRepository   $orderRepository
     * @param Flusher           $flusher
     */
    public function __construct(
        OrderRepository $orderRepository,
        Flusher $flusher
    ) {
        $this->orderRepository = $orderRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        Assert::notNull($command->orderId);
        Assert::notNull($command->amount);

        $order = $this->orderRepository->get(new Id($command->orderId));
        $order->pay($command->amount);

        $client = new Client();
        $response = $client->request('GET', 'https://ya.ru');

        if ($response->getStatusCode() !== 200) {
            throw new DomainException('Failed to pay for the order. Try later.');
        }

        $this->flusher->flush();
    }
}
