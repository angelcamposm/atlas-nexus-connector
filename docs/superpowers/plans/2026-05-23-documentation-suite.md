# Documentation Suite Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a comprehensive documentation suite for the Atlas Nexus Connector.

**Architecture:** A multi-page Markdown documentation structure consisting of a refactored README and specialized guides in the `docs/` directory.

**Tech Stack:** Markdown.

---

### Task 1: Refactor README.md

**Files:**
- Modify: `README.md`

- [ ] **Step 1: Update README.md with the new structure**

```markdown
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
```

- [ ] **Step 2: Commit**

```bash
git add README.md
git commit -m "docs: refactor README.md for better discoverability"
```

---

### Task 2: Create Usage Guide

**Files:**
- Create: `docs/usage.md`

- [ ] **Step 1: Write docs/usage.md**

```markdown
# Usage Guide

The `NexusClient` provides access to several specialized resources.

## Asset Resource

```php
$assets = $client->assets();

// List assets in a repository
$list = $assets->list('maven-releases');

// Get a single asset
$asset = $assets->get('asset-id');

// Delete an asset
$assets->delete('asset-id');
```

## Component Resource

```php
$components = $client->components();

// List components in a repository
$list = $components->list('maven-releases');

// Get a single component
$component = $components->get('component-id');

// Delete a component
$components->delete('component-id');
```

## Repository Resource

```php
$repositories = $client->repositories();

// List all repositories
$list = $repositories->list();

// Get repository details
$repo = $repositories->get('maven-releases');

// Delete a repository
$repositories->delete('maven-releases');

// Invalidate cache
$repositories->invalidateCache('maven-proxy');

// Rebuild index
$repositories->rebuildIndex('maven-releases');
```

## Search Resource

```php
$search = $client->search();

// Search components
$results = $search->search(['q' => 'atlas']);

// Search assets
$results = $search->assets(['format' => 'maven2']);
```

## System Resource

```php
$system = $client->system();

// General status
$status = $system->status();

// Check if writable
$writable = $system->statusWritable();

// Detailed status check
$check = $system->statusCheck();
```
```

- [ ] **Step 2: Commit**

```bash
git add docs/usage.md
git commit -m "docs: create comprehensive usage guide"
```

---

### Task 3: Create Authentication Guide

**Files:**
- Create: `docs/authentication.md`

- [ ] **Step 1: Write docs/authentication.md**

```markdown
# Authentication & Configuration

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

Pass your credentials using the Guzzle `auth` option.

```php
$client = new NexusClient('https://nexus.example.com', [
    'auth' => ['admin', 'admin123'],
]);
```

## Security & Performance Defaults

By default, the client is configured with:
- **Timeout:** 10 seconds
- **Connect Timeout:** 2 seconds
- **SSL Verification:** Enabled (`verify => true`)

You can override these in the `$options` array:

```php
$client = new NexusClient('https://nexus.example.com', [
    'timeout' => 30,
    'verify' => false, // Not recommended for production
]);
```

## Custom Headers

```php
$client = new NexusClient('https://nexus.example.com', [
    'headers' => [
        'User-Agent' => 'MyCustomAgent/1.0',
    ],
]);
```
```

- [ ] **Step 2: Commit**

```bash
git add docs/authentication.md
git commit -m "docs: create authentication and configuration guide"
```

---

### Task 4: Create Error Handling Guide

**Files:**
- Create: `docs/exceptions.md`

- [ ] **Step 1: Write docs/exceptions.md**

```markdown
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
```

- [ ] **Step 2: Commit**

```bash
git add docs/exceptions.md
git commit -m "docs: create error handling guide"
```

---

### Task 5: Update Laravel Integration

**Files:**
- Modify: `docs/laravel-integration.md`

- [ ] **Step 1: Update docs/laravel-integration.md**

```markdown
# Laravel Integration Guide

## 1. Installation

```bash
composer require acamposm/atlas-nexus-connector
```

## 2. Configuration

Add these to your `.env` file:

```env
NEXUS_BASE_URL=https://your-nexus-instance.com
NEXUS_USERNAME=admin
NEXUS_PASSWORD=admin123
```

## 3. Service Provider Binding

Register the `NexusClient` as a singleton in `app/Providers/AppServiceProvider.php`:

```php
use Atlas\Connectors\Nexus\NexusClient;

public function register(): void
{
    $this->app->singleton(NexusClient.class, function ($app) {
        return new NexusClient(
            baseUrl: env('NEXUS_BASE_URL'),
            options: [
                'auth' => [
                    env('NEXUS_USERNAME'),
                    env('NEXUS_PASSWORD'),
                ],
            ]
        );
    });
}
```

## 4. Usage

Inject the client into your classes:

```php
use Atlas\Connectors\Nexus\NexusClient;

class RepositoryController extends Controller
{
    public function __construct(
        private NexusClient $nexus
    ) {}

    public function index()
    {
        return $this->nexus->repositories()->list();
    }
}
```
```

- [ ] **Step 2: Commit**

```bash
git add docs/laravel-integration.md
git commit -m "docs: update laravel integration guide"
```
