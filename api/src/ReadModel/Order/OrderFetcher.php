<?php

declare(strict_types=1);

namespace App\ReadModel\Order;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Driver\Statement;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class OrderFetcher
{
    private Connection $connection;
    private DenormalizerInterface $denormalizer;

    /**
     * OrderFetcher constructor.
     *
     * @param Connection            $connection
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct(Connection $connection, DenormalizerInterface $denormalizer)
    {
        $this->connection = $connection;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @return array|FullOrderView[]
     * @throws ExceptionInterface
     */
    public function all(): array
    {
        /** @var Statement $stmt */
        $stmt = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('orders', 'o')
            ->orderBy('id')
            ->execute();

        $orders = $stmt->fetchAll(FetchMode::ASSOCIATIVE);
        $orderItems = $this->batchLoadOrderItems(array_column($orders, 'id'));

        $orders = array_map(static function (array $order) use ($orderItems) {
            return array_merge($order, [
                'items' => array_filter($orderItems, static function (array $orderItem) use ($order) {
                    return $orderItem['order_id'] === $order['id'];
                }),
            ]);
        }, $orders);

        $result = [];
        foreach ($orders as $order) {
            /** @var FullOrderView $orderObject */
            $orderObject = $this->denormalizer->denormalize($order, FullOrderView::class);
            $result[] = $orderObject;
        }

        return $result;
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    private function batchLoadOrderItems(array $ids): array
    {
        /** @var Statement $stmt */
        $stmt = $this->connection->createQueryBuilder()
            ->select('o_i.*')
            ->from('order_items', 'o_i')
            ->innerJoin('o_i', 'orders', 'o', 'o.id = o_i.order_id')
            ->andWhere('o_i.order_id IN (:order)')
            ->setParameter(':order', $ids, Connection::PARAM_INT_ARRAY)
            ->orderBy('name')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }
}
