<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Resources;

use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;
use Atlas\Connectors\Nexus\NexusClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Base class for all Nexus API resources.
 */
abstract class AbstractResource
{
    /**
     * Create a new resource instance.
     *
     * @param NexusClient $client
     */
    public function __construct(protected NexusClient $client)
    {
    }

    /**
     * Send an API request.
     *
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
        } catch (GuzzleException | \JsonException $e) {
            throw new ApiException($e->getMessage(), (int) $e->getCode());
        }
    }

    /**
     * Parse the API response.
     *
     * @param ResponseInterface $response
     * @return mixed
     * @throws \JsonException
     */
    protected function parseResponse(ResponseInterface $response): mixed
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $contents = $response->getBody()->getContents();

        if ($contents === '') {
            return '';
        }

        if (str_contains($contentType, 'application/json')) {
            return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        }

        return $contents;
    }

    /**
     * Handle client exceptions and map them to specialized exceptions.
     *
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
        $reasonPhrase = $response->getReasonPhrase();

        if ($statusCode === 401) {
            throw new AuthenticationException('Unauthorized', $statusCode, $response);
        }

        $message = sprintf('Nexus API Request Failed: %d %s', $statusCode, $reasonPhrase);

        throw new ApiException($message, $statusCode, $response);
    }
}
