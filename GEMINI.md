# Gemini Project Instructions: Atlas Nexus Connector

This document provides foundational guidance for developing and maintaining the Atlas Nexus Connector project. Adhere to these standards to ensure consistency, quality, and reliability.

## Project Overview

Atlas Nexus Connector is a robust, modern PHP API client for Nexus Repository Manager v3. It follows a resource-based architectural pattern and prioritizes type safety and performance.

## Technical Stack

- **PHP:** ^8.5 (Leveraging modern features like Property Hooks and Constructor Property Promotion).
- **HTTP Client:** Guzzle ^7.8.
- **Testing:** PHPUnit ^11.0 (Target: 100% Code Coverage).
- **Static Analysis:** PHPStan ^2.0 (Level: max).

## Core Mandates & Conventions

### 1. Coding Standards

- **Strict Typing:** Every PHP file MUST start with `declare(strict_types=1);`.
- **PSR Compliance:** Follow PSR-12 for coding style and PSR-4 for autoloading.
- **Type Safety:** Use explicit type hints for all parameters, return types, and properties. Avoid `mixed` unless absolutely necessary.
- **Modern PHP:** Use PHP 8.5 features where appropriate (e.g., property hooks for read-only access to internal instances).

### 2. Architectural Pattern

- **Resource-Based:** The `NexusClient` acts as a gateway to specialized Resource classes (e.g., `AssetResource`, `RepositoryResource`).
- **Resource Extension:** When adding new API capabilities, create a new class in `src/Resources/` extending `AbstractResource` and add a factory method to `NexusClient`.

### 3. Testing & Validation

- **100% Coverage:** All new features or bug fixes MUST include tests that maintain 100% line and method coverage.
- **Empirical Validation:** For bug fixes, always reproduce the failure with a test case before applying the fix.
- **Static Analysis:** Ensure `vendor/bin/phpstan analyse` passes with no errors at the maximum level.

### 4. Documentation

- **PHPDoc:** All public methods and properties MUST have descriptive PHPDoc blocks.
- **Changelog:** Update `CHANGELOG.md` for every release following the [Keep a Changelog](https://keepachangelog.com/) format.
- **Tutorials:** Maintain integration guides in the `docs/` directory.

## Development Workflows

### Running the Test Suite

```bash
composer test
# or
vendor/bin/phpunit
```

### Checking Code Coverage

```bash
vendor/bin/phpunit --coverage-text
```

### Static Analysis

```bash
vendor/bin/phpstan analyse
```

### Adding a New Resource

1. Create `src/Resources/NewResource.php` extending `AbstractResource`.
2. Implement API methods using `$this->request()`.
3. Add a factory method to `src/NexusClient.php`.
4. Create unit tests in `tests/Unit/Resources/NewResourceTest.php`.
5. Update `CHANGELOG.md`.
