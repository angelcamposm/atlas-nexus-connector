<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus;

use Atlas\Connectors\Nexus\Resources\AssetResource;
use Atlas\Connectors\Nexus\Resources\ComponentResource;
use Atlas\Connectors\Nexus\Resources\RepositoryResource;
use Atlas\Connectors\Nexus\Resources\SearchResource;
use Atlas\Connectors\Nexus\Resources\SystemResource;
use GuzzleHttp\Client;

class NexusClient
{
    private Client $httpClientInstance;

    /**
     * Create a new NexusClient instance.
     *
     * @param string $baseUrl The base URL of the Nexus repository.
     * @param array<string, mixed> $options Guzzle client options.
     */
    public function __construct(
        private string $baseUrl,
        private readonly array $options = []
    ) {
        $this->baseUrl = rtrim($baseUrl, '/') . '/service/rest/';
        $this->httpClientInstance = new Client(array_merge([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ], $this->options));
    }

    /**
     * Get the Guzzle HTTP client instance.
     */
    public Client $httpClient {
        get => $this->httpClientInstance;
    }

    /**
     * Get the Asset resource.
     *
     * @return AssetResource
     */
    public function assets(): AssetResource
    {
        return new AssetResource($this);
    }

    /**
     * Get the Component resource.
     *
     * @return ComponentResource
     */
    public function components(): ComponentResource
    {
        return new ComponentResource($this);
    }

    /**
     * Get the Repository resource.
     *
     * @return RepositoryResource
     */
    public function repositories(): RepositoryResource
    {
        return new RepositoryResource($this);
    }

    /**
     * Get the Search resource.
     *
     * @return SearchResource
     */
    public function search(): SearchResource
    {
        return new SearchResource($this);
    }

    /**
     * Get the System resource.
     *
     * @return SystemResource
     */
    public function system(): SystemResource
    {
        return new SystemResource($this);
    }
}
