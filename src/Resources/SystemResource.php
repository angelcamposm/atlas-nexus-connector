<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;

class SystemResource extends AbstractResource
{
    /**
     * Check the health of the Nexus server.
     *
     * @return mixed
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function status(): mixed
    {
        return $this->request('GET', 'v1/status');
    }

    /**
     * Check the status of the Nexus server for write operations.
     *
     * @return mixed
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function statusWritable(): mixed
    {
        return $this->request('GET', 'v1/status/writable');
    }

    /**
     * Check the status of the Nexus server.
     *
     * @return mixed
     *
     * @throws ApiException
     * @throws AuthenticationException
     */
    public function statusCheck(): mixed
    {
        return $this->request('GET', 'v1/status/check');
    }
}
