<?php

declare(strict_types=1);

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection;
use Symfony\Component\Routing;
use Doctrine\DBAL\Types\Type;

require dirname(__DIR__) . '/vendor/autoload.php';

$fileLocator = new FileLocator(__DIR__);

$containerBuilder = new ContainerBuilder();
$loader = new DependencyInjection\Loader\YamlFileLoader($containerBuilder, $fileLocator);
$loader->load('../config/services.yaml');

$loader = new Routing\Loader\YamlFileLoader($fileLocator);
$routes = $loader->load('../config/routes.yaml');
$containerBuilder->setParameter('routes', $routes);

/**
 * @var string $name
 * @var string $className
 */
foreach ($containerBuilder->getParameter('dbal.types') as $name => $className) {
    if (!Type::hasType($name)) {
        Type::addType($name, $className);
    }
}

$containerBuilder->compile(true);

return $containerBuilder;
