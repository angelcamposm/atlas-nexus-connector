<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\nexusClient;
use Atlas\Connectors\Nexus\Resources\AbstractResource;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractResource::class)]
#[UsesClass(nexusClient::class)]
#[UsesClass(ApiException::class)]
class AbstractResourceTest extends TestCase
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

    public function testParseResponseWithNonJsonResponse(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        $this->mockHandler->append(new Response(200, ['Content-Type' => 'text/plain'], 'Plain text'));

        $result = $resource->callRequest('GET', '/test');

        $this->assertEquals('Plain text', $result);
    }

    public function testHandlesGuzzleException(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        $this->mockHandler->append(new ConnectException('Connection error', new Request('GET', '/test')));

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Connection error');

        $resource->callRequest('GET', '/test');
    }

    public function testHandles401Unauthorized(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        $this->mockHandler->append(new Response(401, [], 'Unauthorized'));

        $this->expectException(\Atlas\Connectors\Nexus\Exceptions\AuthenticationException::class);
        $this->expectExceptionMessage('Unauthorized');

        $resource->callRequest('GET', '/test');
    }

    public function testHandlesOtherClientExceptions(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        $this->mockHandler->append(new Response(403, [], 'Forbidden'));

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(403);

        $resource->callRequest('GET', '/test');
    }

    public function testHandlesJsonResponse(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode(['foo' => 'bar'])));

        $result = $resource->callRequest('GET', '/test');

        $this->assertEquals(['foo' => 'bar'], $result);
    }
}
