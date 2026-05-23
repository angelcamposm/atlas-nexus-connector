# Specification: Atlas Nexus Connector Documentation Suite

**Date:** 2026-05-23
**Topic:** Documentation Suite Design
**Status:** Approved

## Goal
To provide a comprehensive, clear, and modern documentation suite for the `atlas-connectors-nexus` package, covering installation, authentication, usage, integration, and error handling.

## Scope
- Refactor `README.md`
- Create `docs/usage.md`
- Create `docs/authentication.md`
- Update `docs/laravel-integration.md`
- Create `docs/exceptions.md`

## Content Strategy

### 1. README.md (Discovery & Quick Start)
- **Header:** Project name and short description.
- **Requirements:** PHP 8.5+, Guzzle 7.8+.
- **Installation:** `composer require acamposm/atlas-nexus-connector`.
- **Quick Start:** A single example showing client initialization and a status check.
- **Table of Contents:** Links to all documentation files in the `docs/` directory.
- **Features:** Bullet points highlighting strict typing, modern PHP, and comprehensive API coverage.
- **Development:** Brief instructions for running tests and static analysis.

### 2. docs/usage.md (Technical Manual)
- **Overview:** Introduction to the resource-based architecture.
- **AssetResource:** Detailed method signatures and examples for `list()`, `get()`, and `delete()`.
- **ComponentResource:** Detailed method signatures and examples for `list()`, `get()`, and `delete()`.
- **RepositoryResource:** Detailed method signatures and examples for `list()`, `get()`, `delete()`, `invalidateCache()`, and `rebuildIndex()`.
- **SearchResource:** Detailed method signatures and examples for `search()` and `assets()`.
- **SystemResource:** Detailed method signatures and examples for `status()`, `statusWritable()`, and `statusCheck()`.

### 3. docs/authentication.md (Connection & Auth)
- **NexusClient Initialization:** Explaining the `baseUrl` and `$options` parameters.
- **Basic Authentication:** Clear example of setting up credentials via Guzzle `auth`.
- **Custom Options:** How to pass extra Guzzle configuration.
- **Security Defaults:** Documentation on the new 10s/2s timeouts and SSL verification enforcement.

### 4. docs/laravel-integration.md (Framework Support)
- **Installation:** Re-confirming the composer command.
- **Configuration:** Example `config/services.php` and `.env` setup.
- **Service Provider:** Modern binding example in `AppServiceProvider`.
- **Usage:** Example Controller injecting `NexusClient`.
- **Testing:** Brief note on how to mock the client in Laravel tests.

### 5. docs/exceptions.md (Error Handling)
- **Hierarchy:** Overview of `NexusException`, `ApiException`, and `AuthenticationException`.
- **ApiException Details:** How to extract status codes and the `ResponseInterface`.
- **Security:** Explanation of how error messages are sanitized to prevent data leakage.
- **Best Practices:** Recommended patterns for catching and handling exceptions in production.

## Standards
- **Tone:** Professional, direct, and developer-focused.
- **Style:** GitHub-flavored Markdown.
- **Code Blocks:** All examples must use correct syntax highlighting and follow the package's coding style (PHP 8.5).
- **Consistency:** Ensure all class names and namespaces match the current codebase.

---
**Review Checklist:**
- [x] Placeholder scan: No "TBD" or "TODO".
- [x] Internal consistency: All filenames and structures match.
- [x] Scope check: Focused on the 5 primary documents.
- [x] Ambiguity check: Clear distinction between the purpose of each file.
