<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

class ApiException extends Exception implements NexusException
{
    public function __construct(
        string $message,
        int $code = 0,
        private readonly ?ResponseInterface $response = null
    ) {
        parent::__construct($message, $code);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
