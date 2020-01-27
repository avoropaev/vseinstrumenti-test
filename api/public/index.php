<?php

declare(strict_types=1);

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing;

require __DIR__ . '/../vendor/autoload.php';

$fileLocator = new FileLocator(__DIR__);

$containerBuilder = new ContainerBuilder();
$loader = new DependencyInjection\Loader\YamlFileLoader($containerBuilder, $fileLocator);
$loader->load('../config/services.yaml');

$loader = new Routing\Loader\YamlFileLoader($fileLocator);
$routes = $loader->load('../config/routes.yaml');
$containerBuilder->setParameter('routes', $routes);

$containerBuilder->compile();

/** @var HttpKernel $kernel */
$kernel = $containerBuilder->get('http_kernel');

$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
