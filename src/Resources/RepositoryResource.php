<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;

class RepositoryResource extends AbstractResource
{
    /**
     * List repositories.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function list(): array
    {
        return $this->request('GET', 'v1/repositories');
    }

    /**
     * Get repository details.
     *
     * @param string $repositoryName Name of the repository to get.
     *
     * @return array<string, mixed>
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function get(string $repositoryName): array
    {
        return $this->request('GET', "v1/repositories/$repositoryName");
    }

    /**
     * Delete repository of any format.
     *
     * @param string $repositoryName Name of the repository to delete.
     *
     * @return void
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function delete(string $repositoryName): void
    {
        $this->request('DELETE', "v1/repositories/$repositoryName");
    }

    /**
     * Invalidate repository cache. Proxy or group repositories only.
     *
     * @param string $repositoryName Name of the repository to invalidate cache.
     *
     * @return void
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function invalidateCache(string $repositoryName): void
    {
        $this->request('POST', "v1/repositories/$repositoryName/invalidate-cache");
    }

    /**
     * Schedule a 'Repair - Rebuild repository search' Task. Hosted or proxy repositories only.
     *
     * @param string $repositoryName Name of the repository to rebuild index.
     *
     * @return void
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function rebuildIndex(string $repositoryName): void
    {
        $this->request('POST', "v1/repositories/$repositoryName/rebuild-index");
    }
}
