<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;

class ComponentResource extends AbstractResource
{
    /**
     * List components.
     *
     * @param string $repository Repository from which you would like to retrieve components.
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

        return $this->request('GET', 'v1/components', ['query' => $query]);
    }

    /**
     * Get a single component.
     *
     * @param string $id ID of the component to retrieve.
     *
     * @return array<string, mixed>
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function get(string $id): array
    {
        return $this->request('GET', "v1/components/$id");
    }

    /**
     * Delete a single component.
     *
     * @param string $id ID of the component to delete.
     *
     * @return void
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function delete(string $id): void
    {
        $this->request('DELETE', "v1/components/$id");
    }
}
