# Usage Guide

The `NexusClient` provides access to several specialized resources. All resource methods return an `array` of data or `void` on success, and throw an exception on failure.

## Initialization

```php
use Atlas\Connectors\Nexus\NexusClient;

$client = new NexusClient('https://nexus.example.com', [
    'auth' => ['username', 'password'],
]);
```

## Asset Resource

```php
$assets = $client->assets();

// List assets in a repository (returns array)
$list = $assets->list('maven-releases');

// Get a single asset (returns array)
$asset = $assets->get('asset-id');

// Delete an asset (returns void)
$assets->delete('asset-id');
```

## Component Resource

```php
$components = $client->components();

// List components in a repository (returns array)
$list = $components->list('maven-releases');

// Get a single component (returns array)
$component = $components->get('component-id');

// Delete a component (returns void)
$components->delete('component-id');
```

## Repository Resource

```php
$repositories = $client->repositories();

// List all repositories (returns array)
$list = $repositories->list();

// Get repository details (returns array)
$repo = $repositories->get('maven-releases');

// Delete a repository (returns void)
$repositories->delete('maven-releases');

// Invalidate cache (returns void)
$repositories->invalidateCache('maven-proxy');

// Rebuild index (returns void)
$repositories->rebuildIndex('maven-releases');
```

## Search Resource

The Search resource allows you to query components and assets using various criteria such as `format`, `group`, `name`, `version`, and more.

```php
$search = $client->search();

// Search components with criteria
$results = $search->search([
    'q' => 'atlas',
    'format' => 'maven2',
    'repository' => 'maven-releases'
]);

// Search assets
$results = $search->assets([
    'format' => 'npm',
    'name' => 'nexus-connector'
]);
```

## System Resource

```php
$system = $client->system();

// General status (returns mixed)
$status = $system->status();

// Check if writable (returns mixed)
$writable = $system->statusWritable();

// Detailed status check (returns mixed)
$check = $system->statusCheck();
```
