<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Resources;

use Atlas\Connectors\Nexus\NexusClient;
use Atlas\Connectors\Nexus\Resources\RepositoryResource;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RepositoryResource::class)]
#[UsesClass(NexusClient::class)]
#[UsesClass(\Atlas\Connectors\Nexus\Resources\AbstractResource::class)]
class RepositoryResourceTest extends TestCase
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

    public function testListRepositories(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode([['name' => 'repo1']])));

        $result = $this->client->repositories()->list();

        $this->assertCount(1, $result);
        $this->assertEquals('/service/rest/v1/repositories', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testGetRepository(): void
    {
        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['name' => 'repo1'])));

        $result = $this->client->repositories()->get('repo1');

        $this->assertEquals('repo1', $result['name']);
        $this->assertEquals('/service/rest/v1/repositories/repo1', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testDeleteRepository(): void
    {
        $this->mockHandler->append(new Response(204));

        $this->client->repositories()->delete('repo1');

        $this->assertEquals('DELETE', $this->mockHandler->getLastRequest()->getMethod());
        $this->assertEquals('/service/rest/v1/repositories/repo1', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testInvalidateCache(): void
    {
        $this->mockHandler->append(new Response(204));

        $this->client->repositories()->invalidateCache('repo1');

        $this->assertEquals('POST', $this->mockHandler->getLastRequest()->getMethod());
        $this->assertEquals('/service/rest/v1/repositories/repo1/invalidate-cache', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }

    public function testRebuildIndex(): void
    {
        $this->mockHandler->append(new Response(204));

        $this->client->repositories()->rebuildIndex('repo1');

        $this->assertEquals('POST', $this->mockHandler->getLastRequest()->getMethod());
        $this->assertEquals('/service/rest/v1/repositories/repo1/rebuild-index', $this->mockHandler->getLastRequest()->getUri()->getPath());
    }
}
