<?php

declare(strict_types=1);

namespace Test\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernel;

class WebTestCase extends TestCase
{
    /**
     * @param string $uri
     * @param string $method
     *
     * @return Request
     */
    protected static function json(string $uri, string $method): Request
    {
        $request = self::request($uri, $method);
        $request->setRequestFormat('json');

        return $request;
    }

    /**
     * @param string $uri
     * @param string $method
     *
     * @return Request
     */
    protected static function request(string $uri, string $method): Request
    {
        return Request::create($uri, $method);
    }

    /**
     * @return HttpKernel
     */
    protected function app(): HttpKernel
    {
        /** @var HttpKernel $kernel */
        $kernel = $this->container()->get('http_kernel');

        return $kernel;
    }

    /**
     * @return ContainerInterface
     */
    private function container(): ContainerInterface
    {
        /** @var ContainerInterface */
        return require __DIR__ . '/../../config/container.php';
    }
}
