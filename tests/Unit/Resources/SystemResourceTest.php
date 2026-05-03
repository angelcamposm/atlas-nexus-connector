<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;
use Atlas\Connectors\Nexus\nexusClient;
use Atlas\Connectors\Nexus\Resources\SystemResource;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SystemResource::class)]
#[UsesClass(nexusClient::class)]
#[UsesClass(\Atlas\Connectors\Nexus\Resources\AbstractResource::class)]
class SystemResourceTest extends TestCase
{
    private MockHandler $mockHandler;
    private nexusClient $client;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $this->client = new nexusClient('https://nexus.example.com', [
            'handler' => $handlerStack,
        ]);
    }

    public function testStatusReturnsString(): void
    {
        $this->mockHandler->append(new Response(200, [], 'OK'));

        $status = $this->client->system()->status();

        $this->assertEquals('OK', $status);
        $this->assertEquals('GET', $this->mockHandler->getLastRequest()->getMethod());
        $this->assertEquals('/service/rest/v1/status', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testStatusWritableReturnsString(): void
    {
        $this->mockHandler->append(new Response(200, [], 'OK'));

        $status = $this->client->system()->statusWritable();

        $this->assertEquals('OK', $status);
        $this->assertEquals('GET', $this->mockHandler->getLastRequest()->getMethod());
        $this->assertEquals('/service/rest/v1/status/writable', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testStatusCheckReturnsString(): void
    {
        $this->mockHandler->append(new Response(200, [], 'OK'));

        $status = $this->client->system()->statusCheck();

        $this->assertEquals('OK', $status);
        $this->assertEquals('GET', $this->mockHandler->getLastRequest()->getMethod());
        $this->assertEquals('/service/rest/v1/status/check', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testHandlesJsonResponse(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['status' => 'healthy'])));

        $status = $this->client->system()->status();

        $this->assertIsArray($status);
        $this->assertEquals('healthy', $status['status']);
    }

    public function testThrowsAuthenticationExceptionOn401(): void
    {
        $this->mockHandler->append(new Response(401, [], 'Unauthorized'));

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Unauthorized');

        $this->client->system()->status();
    }

    public function testThrowsApiExceptionOnOtherErrors(): void
    {
        $this->mockHandler->append(new Response(500, [], 'Internal Server Error'));

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(500);

        $this->client->system()->status();
    }
}
