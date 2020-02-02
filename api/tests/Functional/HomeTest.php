<?php

declare(strict_types=1);

namespace Test\Functional;

use Exception;
use JSend\JSendResponse;

class HomeTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testMethodFailed(): void
    {
        $response = $this->app()->handle(self::json('/ping', 'POST'));

        self::assertEquals(405, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('/ping', 'GET'));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('application/json', $response->headers->get('Content-Type'));
        self::assertEquals(JSendResponse::success(), $response->getContent());
    }
}
