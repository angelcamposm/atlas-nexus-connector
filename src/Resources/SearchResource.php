<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;

class SearchResource extends AbstractResource
{
    /**
     * Search components.
     *
     * @param array<string, mixed> $criteria Search criteria.
     *
     * @return array<string, mixed>
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function search(array $criteria = []): array
    {
        return $this->request('GET', 'v1/search', ['query' => $criteria]);
    }

    /**
     * Search assets.
     *
     * @param array<string, mixed> $criteria Search criteria.
     *
     * @return array<string, mixed>
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function assets(array $criteria = []): array
    {
        return $this->request('GET', 'v1/search/assets', ['query' => $criteria]);
    }
}
