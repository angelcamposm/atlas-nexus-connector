<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Resources;

use Atlas\Connectors\Nexus\NexusClient;
use Atlas\Connectors\Nexus\Resources\ComponentResource;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ComponentResource::class)]
#[UsesClass(NexusClient::class)]
#[UsesClass(\Atlas\Connectors\Nexus\Resources\AbstractResource::class)]
class ComponentResourceTest extends TestCase
{
    private MockHandler $mockHandler;
    private NexusClient $client;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $this->client = new NexusClient('https://nexus.example.com', [
            'handler' => $handlerStack,
        ]);
    }

    public function testListComponent(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['items' => []])));

        $result = $this->client->components()->list('maven-releases');

        $this->assertEquals('/service/rest/v1/components', $this->mockHandler->getLastRequest()->getUri()->getPath());
        $this->assertEquals('repository=maven-releases', $this->mockHandler->getLastRequest()->getUri()->getQuery());
    }

    public function testListComponentWithContinuationToken(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['items' => []])));

        $this->client->components()->list('maven-releases', 'token123');

        $this->assertStringContainsString('continuationToken=token123', $this->mockHandler->getLastRequest()->getUri()->getQuery());
    }

    public function testGetComponent(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => '123'])));

        $result = $this->client->components()->get('123');

        $this->assertEquals('123', $result['id']);
        $this->assertEquals('/service/rest/v1/components/123', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testDeleteComponent(): void
    {
        $this->mockHandler->append(new Response(204));

        $this->client->components()->delete('123');

        $this->assertEquals('DELETE', $this->mockHandler->getLastRequest()->getMethod());
        $this->assertEquals('/service/rest/v1/components/123', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }
}
