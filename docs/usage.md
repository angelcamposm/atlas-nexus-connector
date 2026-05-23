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
