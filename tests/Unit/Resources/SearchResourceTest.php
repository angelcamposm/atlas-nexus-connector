<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Resources;

use Atlas\Connectors\Nexus\nexusClient;
use Atlas\Connectors\Nexus\Resources\SearchResource;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SearchResource::class)]
#[UsesClass(nexusClient::class)]
#[UsesClass(\Atlas\Connectors\Nexus\Resources\AbstractResource::class)]
class SearchResourceTest extends TestCase
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

    public function testSearch(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['items' => []])));

        $result = $this->client->search()->search(['q' => 'test']);

        $this->assertEquals('/service/rest/v1/search', $this->mockHandler->getLastRequest()->getUri()->getPath());
        $this->assertEquals('q=test', $this->mockHandler->getLastRequest()->getUri()->getQuery());
    }

    public function testSearchAssets(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['items' => []])));

        $result = $this->client->search()->assets(['q' => 'test']);

        $this->assertEquals('/service/rest/v1/search/assets', $this->mockHandler->getLastRequest()->getUri()->getPath());
        $this->assertEquals('q=test', $this->mockHandler->getLastRequest()->getUri()->getQuery());
    }
}
