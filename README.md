# Atlas Nexus Connector

A robust, modern PHP API client for Nexus Repository Manager v3.

## Requirements

- PHP ^8.5
- Guzzle ^7.8

## Installation

```bash
composer require acamposm/atlas-nexus-connector
```

## Usage

```php
use Atlas\Connectors\Nexus\nexusClient;

$client = new nexusClient('https://nexus.example.com');

// Check system status
$status = $client->system()->status();

// List repositories
$repositories = $client->repositories()->list();

// Search components
$components = $client->search()->search(['q' => 'atlas']);

// List assets in a repository
$assets = $client->assets()->list('maven-releases');
```

## Features

- **Strict Typing:** All files use `declare(strict_types=1)`.
- **Modern PHP:** Leverages PHP 8.5 features like Property Hooks.
- **Scalable Architecture:** Resource-based pattern for easy expansion.
- **Comprehensive API Coverage:**
    - **Assets:** List, get, and delete assets.
    - **Components:** List, get, and delete components.
    - **Repositories:** List, get, delete, invalidate cache, and rebuild index.
    - **Search:** Flexible search for components and assets.
    - **System:** Health and status checks.
- **Robust Error Handling:** Dedicated exception classes for different API scenarios.
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
