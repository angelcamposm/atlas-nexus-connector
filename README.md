# Atlas Nexus Connector

A robust, modern PHP API client for Nexus Repository Manager v3.

## Requirements

- PHP ^8.5
- Guzzle ^7.8

## Installation

```bash
composer require acamposm/atlas-nexus-connector
```

## Quick Start

```php
use Atlas\Connectors\Nexus\NexusClient;

$client = new NexusClient('https://nexus.example.com', [
    'auth' => ['username', 'password'],
]);

// Check system status
$status = $client->system()->status();
```

## Documentation

- [Usage Guide](docs/usage.md)
- [Authentication](docs/authentication.md)
- [Laravel Integration](docs/laravel-integration.md)
- [Error Handling](docs/exceptions.md)

## Features

- **Strict Typing:** All files use `declare(strict_types=1)`.
- **Modern PHP:** Leverages PHP 8.5 features like Property Hooks.
- **Scalable Architecture:** Resource-based pattern for easy expansion.
- **Comprehensive API Coverage:** Assets, Components, Repositories, Search, and System.
- **Robust Error Handling:** Sanitized error messages to prevent sensitive data leakage.
- **100% Test Coverage:** Rigorously tested with PHPUnit.

## Development

### Running Tests

```bash
composer install
vendor/bin/phpunit
```

### Static Analysis

```bash
vendor/bin/phpstan analyse
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
