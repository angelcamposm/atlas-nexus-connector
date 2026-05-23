<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\NexusClient;
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
#[UsesClass(NexusClient::class)]
#[UsesClass(ApiException::class)]
class AbstractResourceTest extends TestCase
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

    public function testHandlesClientExceptionsWithSanitizedMessage(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        $this->mockHandler->append(new Response(403, [], 'Forbidden'));

        try {
            $resource->callRequest('GET', '/test');
            $this->fail('ApiException was not thrown');
        } catch (ApiException $e) {
            $this->assertEquals(403, $e->getCode());
            $this->assertStringNotContainsString('GET /test', $e->getMessage());
            $this->assertEquals('Nexus API Request Failed: 403 Forbidden', $e->getMessage());
        }
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

    public function testHandlesInvalidJsonResponse(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], 'invalid json'));

        $this->expectException(ApiException::class);
        $resource->callRequest('GET', '/test');
    }

    public function testHandlesEmptyResponse(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        $this->mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], ''));

        $result = $resource->callRequest('GET', '/test');
        $this->assertEquals('', $result);
    }
}
