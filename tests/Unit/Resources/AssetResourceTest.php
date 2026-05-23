<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Resources;

use Atlas\Connectors\Nexus\NexusClient;
use Atlas\Connectors\Nexus\Resources\AssetResource;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AssetResource::class)]
#[UsesClass(NexusClient::class)]
#[UsesClass(\Atlas\Connectors\Nexus\Resources\AbstractResource::class)]
class AssetResourceTest extends TestCase
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

    public function testListAssets(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['items' => []])));

        $result = $this->client->assets()->list('maven-releases');

        $this->assertEquals('/service/rest/v1/assets', $this->mockHandler->getLastRequest()->getUri()->getPath());
        $this->assertEquals('repository=maven-releases', $this->mockHandler->getLastRequest()->getUri()->getQuery());
    }

    public function testListAssetsWithContinuationToken(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['items' => []])));

        $this->client->assets()->list('maven-releases', 'token123');

        $this->assertStringContainsString('continuationToken=token123', $this->mockHandler->getLastRequest()->getUri()->getQuery());
    }

    public function testGetAsset(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => '123'])));

        $result = $this->client->assets()->get('123');

        $this->assertEquals('123', $result['id']);
        $this->assertEquals('/service/rest/v1/assets/123', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testDeleteAsset(): void
    {
        $this->mockHandler->append(new Response(204));

        $this->client->assets()->delete('123');

        $this->assertEquals('DELETE', $this->mockHandler->getLastRequest()->getMethod());
        $this->assertEquals('/service/rest/v1/assets/123', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }
}
