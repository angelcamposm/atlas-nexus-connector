# Authentication & Configuration

The `NexusClient` is designed to be flexible, allowing you to pass any valid [Guzzle configuration options](https://docs.guzzlephp.org/en/stable/request-options.html) during initialization.

## Client Initialization

The `NexusClient` constructor accepts a base URL and an optional array of Guzzle options.

```php
use Atlas\Connectors\Nexus\NexusClient;

$client = new NexusClient(
    baseUrl: 'https://nexus.example.com',
    options: []
);
```

## Basic Authentication

Pass your credentials using the Guzzle `auth` option. This is the standard way to authenticate with Nexus Repository Manager.

```php
$client = new NexusClient('https://nexus.example.com', [
    'auth' => ['admin', 'admin123'],
]);
```

## Security & Performance Defaults

By default, the client is configured with secure and performant defaults to ensure reliability:

- **Timeout:** 10 seconds (Total request timeout)
- **Connect Timeout:** 2 seconds (Timeout for establishing the connection)
- **SSL Verification:** Enabled (`verify => true`)

You can override these in the `$options` array if your environment requires different settings:

```php
$client = new NexusClient('https://nexus.example.com', [
    'timeout' => 30,
    'verify' => '/path/to/cert.pem', // Custom CA bundle
]);
```

> [!WARNING]
> Disabling SSL verification (`'verify' => false`) is strongly discouraged in production environments as it exposes your credentials to man-in-the-middle attacks.

## Custom Headers

You can also pass custom headers that will be sent with every request:

```php
$client = new NexusClient('https://nexus.example.com', [
    'headers' => [
        'User-Agent' => 'MyCustomApp/1.0',
        'X-Custom-Header' => 'Value',
    ],
]);
```
