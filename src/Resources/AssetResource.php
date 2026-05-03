<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;

class AssetResource extends AbstractResource
{
    /**
     * List assets.
     *
     * @param string $repository Repository from which you would like to retrieve assets.
     * @param string|null $continuationToken A token returned by a prior request.
     *
     * @return array<string, mixed>
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function list(string $repository, ?string $continuationToken = null): array
    {
        $query = ['repository' => $repository];

        if ($continuationToken !== null) {
            $query['continuationToken'] = $continuationToken;
        }

        return $this->request('GET', 'v1/assets', ['query' => $query]);
    }

    /**
     * Get a single asset.
     *
     * @param string $id Id of the asset to get.
     *
     * @return array<string, mixed>
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function get(string $id): array
    {
        return $this->request('GET', "v1/assets/{$id}");
    }

    /**
     * Delete a single asset.
     *
     * @param string $id Id of the asset to delete.
     *
     * @return void
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function delete(string $id): void
    {
        $this->request('DELETE', "v1/assets/{$id}");
    }
}
