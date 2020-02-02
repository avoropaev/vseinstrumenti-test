<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product\Id;
use App\Entity\Product\Product;
use App\Exception\EntityNotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use RuntimeException;

class ProductRepository
{
    private ObjectRepository $repository;
    private Connection $connection;
    private EntityManagerInterface $em;

    /**
     * ProductRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Product::class);
        $this->connection = $em->getConnection();
        $this->em = $em;
    }

    /**
     * @param Id $id
     *
     * @return Product
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function get(Id $id): Product
    {
        /** @var Product $product */
        if (null === $product = $this->repository->find($id->value())) {
            throw new EntityNotFoundException('Product is not found.');
        }

        return $product;
    }

    /**
     * @param Product $product
     */
    public function add(Product $product): void
    {
        $this->em->persist($product);
    }

    /**
     * @param Product $product
     */
    public function remove(Product $product): void
    {
        $this->em->remove($product);
    }

    /**
     * @return Id
     */
    public function nextId(): Id
    {
        try {
            return new Id((int)$this->connection->query('SELECT nextval(\'products_seq\')')->fetchColumn());
        } catch (DBALException $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
