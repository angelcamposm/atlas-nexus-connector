<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit;

use Atlas\Connectors\Nexus\NexusClient;
use Atlas\Connectors\Nexus\Resources\AssetResource;
use Atlas\Connectors\Nexus\Resources\ComponentResource;
use Atlas\Connectors\Nexus\Resources\RepositoryResource;
use Atlas\Connectors\Nexus\Resources\SearchResource;
use Atlas\Connectors\Nexus\Resources\SystemResource;
use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NexusClient::class)]
class NexusClientTest extends TestCase
{
    public function testClientInitialization(): void
    {
        $client = new NexusClient('https://nexus.example.com');

        $this->assertInstanceOf(NexusClient::class, $client);
        $this->assertInstanceOf(Client::class, $client->httpClient);
        $this->assertEquals('https://nexus.example.com/service/rest/', (string) $client->httpClient->getConfig('base_uri'));
    }

    public function testClientNormalizesBaseUrl(): void
    {
        $client = new NexusClient('https://nexus.example.com/');
        $this->assertEquals('https://nexus.example.com/service/rest/', (string) $client->httpClient->getConfig('base_uri'));
    }

    public function testSystemResourceAccess(): void
    {
        $client = new NexusClient('https://nexus.example.com');
        $this->assertInstanceOf(SystemResource::class, $client->system());
    }

    public function testAssetsResourceAccess(): void
    {
        $client = new NexusClient('https://nexus.example.com');
        $this->assertInstanceOf(AssetResource::class, $client->assets());
    }

    public function testComponentsResourceAccess(): void
    {
        $client = new NexusClient('https://nexus.example.com');
        $this->assertInstanceOf(ComponentResource::class, $client->components());
    }

    public function testRepositoriesResourceAccess(): void
    {
        $client = new NexusClient('https://nexus.example.com');
        $this->assertInstanceOf(RepositoryResource::class, $client->repositories());
    }

    public function testSearchResourceAccess(): void
    {
        $client = new NexusClient('https://nexus.example.com');
        $this->assertInstanceOf(SearchResource::class, $client->search());
    }

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
}
