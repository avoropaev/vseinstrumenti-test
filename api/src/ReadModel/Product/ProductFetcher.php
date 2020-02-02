<?php

declare(strict_types=1);

namespace App\ReadModel\Product;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\FetchMode;

class ProductFetcher
{
    private Connection $connection;

    /**
     * ProductFetcher constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array|FullProductView[]
     * @psalm-suppress InvalidScalarArgument
     */
    public function all(): array
    {
        /** @var Statement $stmt */
        $stmt = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('products', 'p')
            ->orderBy('id')
            ->execute();

        return $stmt->fetchAll(FetchMode::CUSTOM_OBJECT, FullProductView::class);
    }
}
