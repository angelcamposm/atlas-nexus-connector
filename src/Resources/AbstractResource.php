<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;
use Atlas\Connectors\Nexus\nexusClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractResource
{
    public function __construct(protected nexusClient $client)
    {
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array<string, mixed> $options
     * @return mixed
     * @throws ApiException
     * @throws AuthenticationException
     */
    protected function request(string $method, string $uri, array $options = []): mixed
    {
        try {
            $response = $this->client->httpClient->request($method, $uri, $options);

            return $this->parseResponse($response);
        } catch (ClientException $e) {
            $this->handleClientException($e);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), (int) $e->getCode());
        }
    }

    protected function parseResponse(ResponseInterface $response): mixed
    {
        $contentType = $response->getHeaderLine('Content-Type');

        if (str_contains($contentType, 'application/json')) {
            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param ClientException $e
     * @return never
     * @throws ApiException
     * @throws AuthenticationException
     */
    protected function handleClientException(ClientException $e): never
    {
        /** @var ResponseInterface $response */
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();

        if ($statusCode === 401) {
            throw new AuthenticationException('Unauthorized', $statusCode, $response);
        }

        throw new ApiException($e->getMessage(), $statusCode, $response);
    }
}
