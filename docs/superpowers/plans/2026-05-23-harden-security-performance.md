# Harden Security & Performance Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement security and performance hardening for the Nexus API client by adding default timeouts, enforcing SSL verification, and sanitizing error messages.

**Architecture:** We will modify the `NexusClient` constructor to include default Guzzle options and update `AbstractResource` to handle exceptions more securely by avoiding the leakage of sensitive Guzzle error messages.

**Tech Stack:** PHP 8.5, Guzzle 7.8, PHPUnit 11.0.

---

### Task 1: Harden NexusClient Defaults

**Files:**
- Modify: `src/NexusClient.php`
- Test: `tests/Unit/NexusClientTest.php`

- [ ] **Step 1: Write failing tests for timeouts and SSL verification**

Update `tests/Unit/NexusClientTest.php`:
```php
    public function testClientHasDefaultSecurityAndPerformanceSettings(): void
    {
        $client = new NexusClient('https://nexus.example.com');

        $this->assertEquals(10, $client->httpClient->getConfig('timeout'));
        $this->assertEquals(2, $client->httpClient->getConfig('connect_timeout'));
        $this->assertTrue($client->httpClient->getConfig('verify'));
    }

    public function testClientAllowsOverridingDefaults(): void
    {
        $client = new NexusClient('https://nexus.example.com', [
            'timeout' => 30,
            'verify' => false,
        ]);

        $this->assertEquals(30, $client->httpClient->getConfig('timeout'));
        $this->assertEquals(2, $client->httpClient->getConfig('connect_timeout'));
        $this->assertFalse($client->httpClient->getConfig('verify'));
    }
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `vendor/bin/phpunit tests/Unit/NexusClientTest.php`
Expected: FAIL (Assertions on timeout, connect_timeout, and verify will fail).

- [ ] **Step 3: Update NexusClient constructor with defaults**

Modify `src/NexusClient.php`:
```php
    public function __construct(
        private string $baseUrl,
        private readonly array $options = []
    ) {
        $this->baseUrl = rtrim($baseUrl, '/') . '/service/rest/';
        $this->httpClientInstance = new Client(array_merge([
            'base_uri' => $this->baseUrl,
            'timeout' => 10,
            'connect_timeout' => 2,
            'verify' => true,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ], $this->options));
    }
```

- [ ] **Step 4: Run tests to verify they pass**

Run: `vendor/bin/phpunit tests/Unit/NexusClientTest.php`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add src/NexusClient.php tests/Unit/NexusClientTest.php
git commit -m "security: add default timeouts and enforce SSL verification"
```

---

### Task 2: Sanitize Exception Messages

**Files:**
- Modify: `src/Resources/AbstractResource.php`
- Test: `tests/Unit/Resources/AbstractResourceTest.php`

- [ ] **Step 1: Write failing test for sanitized exception message**

Update `tests/Unit/Resources/AbstractResourceTest.php` (modify `testHandlesOtherClientExceptions` or add a new one):
```php
    public function testHandlesClientExceptionsWithSanitizedMessage(): void
    {
        $resource = new class($this->client) extends AbstractResource {
            public function callRequest(string $method, string $uri): mixed
            {
                return $this->request($method, $uri);
            }
        };

        // Guzzle ClientException messages often include the full request/response summary
        // which might contain sensitive headers like Authorization.
        $this->mockHandler->append(new Response(403, [], 'Forbidden'));

        try {
            $resource->callRequest('GET', '/test');
            $this->fail('ApiException was not thrown');
        } catch (ApiException $e) {
            $this->assertEquals(403, $e->getCode());
            // We expect a simple, non-leaky message
            $this->assertStringNotContainsString('GET /test', $e->getMessage());
            $this->assertEquals('Nexus API Request Failed: 403 Forbidden', $e->getMessage());
        }
    }
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `vendor/bin/phpunit tests/Unit/Resources/AbstractResourceTest.php`
Expected: FAIL (The message will likely be the default Guzzle ClientException message).

- [ ] **Step 3: Update AbstractResource::handleClientException**

Modify `src/Resources/AbstractResource.php`:
```php
    protected function handleClientException(ClientException $e): never
    {
        /** @var ResponseInterface $response */
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();

        if ($statusCode === 401) {
            throw new AuthenticationException('Unauthorized', $statusCode, $response);
        }

        $message = sprintf('Nexus API Request Failed: %d %s', $statusCode, $reasonPhrase);

        throw new ApiException($message, $statusCode, $response);
    }
```

- [ ] **Step 4: Run tests to verify they pass**

Run: `vendor/bin/phpunit tests/Unit/Resources/AbstractResourceTest.php`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add src/Resources/AbstractResource.php tests/Unit/Resources/AbstractResourceTest.php
git commit -m "security: sanitize exception messages to prevent sensitive data leakage"
```
