<?php

declare(strict_types=1);

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Helper\HelperSet;

require dirname(__DIR__) . '/vendor/autoload.php';

$containerBuilder = require dirname(__DIR__) . '/config/container.php';

/** @var EntityManager $entityManager */
$entityManager = $containerBuilder->get('entity_manager');

return new HelperSet([
    'em' => new EntityManagerHelper($entityManager),
    'db' => new ConnectionHelper($entityManager->getConnection())
]);
