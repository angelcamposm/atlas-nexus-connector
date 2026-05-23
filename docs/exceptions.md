# Error Handling

All exceptions thrown by the package implement the `Atlas\Connectors\Nexus\Exceptions\NexusException` interface.

## Exception Hierarchy

- `NexusException` (Interface)
    - `ApiException`: Thrown for general API errors (4xx and 5xx).
    - `AuthenticationException`: Specialized for 401 Unauthorized errors.

## Handling API Errors

```php
use Atlas\Connectors\Nexus\Exceptions\ApiException;
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;

try {
    $repositories = $client->repositories()->list();
} catch (AuthenticationException $e) {
    // Handle invalid credentials
} catch (ApiException $e) {
    // Get the status code
    $code = $e->getCode();
    
    // Get the sanitized message
    $message = $e->getMessage();
    
    // Get the PSR-7 response object
    $response = $e->getResponse();
}
```

## Security & Sanitization

To prevent accidental leakage of sensitive information (like credentials in request headers), the package sanitizes error messages. 

Instead of the raw Guzzle error message, you will receive a clean summary:
`Nexus API Request Failed: 403 Forbidden`

The full response object is still available via `$e->getResponse()` for debugging in secure environments.
