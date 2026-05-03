<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Exceptions;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ApiException::class)]
class ApiExceptionTest extends TestCase
{
    public function testApiExceptionStoresResponse(): void
    {
        $response = new Response(500, [], 'Error');
        $exception = new ApiException('Test error', 500, $response);

        $this->assertEquals('Test error', $exception->getMessage());
        $this->assertEquals(500, $exception->getCode());
        $this->assertSame($response, $exception->getResponse());
    }

    public function testApiExceptionWorksWithoutResponse(): void
    {
        $exception = new ApiException('Test error', 500);

        $this->assertEquals('Test error', $exception->getMessage());
        $this->assertNull($exception->getResponse());
    }
}
