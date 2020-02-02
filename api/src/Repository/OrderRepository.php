<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order\Id;
use App\Entity\Order\Order;
use App\Exception\EntityNotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use RuntimeException;

class OrderRepository
{
    private ObjectRepository $repository;
    private Connection $connection;
    private EntityManagerInterface $em;

    /**
     * OrderRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Order::class);
        $this->connection = $em->getConnection();
        $this->em = $em;
    }

    /**
     * @param Id $id
     *
     * @return Order
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function get(Id $id): Order
    {
        /** @var Order $order */
        if (null === $order = $this->repository->find($id->value())) {
            throw new EntityNotFoundException('Order is not found.');
        }

        return $order;
    }

    /**
     * @param Order $order
     */
    public function add(Order $order): void
    {
        $this->em->persist($order);
    }

    /**
     * @param Order $order
     */
    public function remove(Order $order): void
    {
        $this->em->remove($order);
    }

    /**
     * @return Id
     */
    public function nextId(): Id
    {
        try {
            return new Id((int)$this->connection->query('SELECT nextval(\'orders_seq\')')->fetchColumn());
        } catch (DBALException $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
