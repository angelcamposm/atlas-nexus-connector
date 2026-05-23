# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-05-03

### Added
- Initial release of Atlas Nexus Connector.
- Support for Nexus Repository Manager v3 API.
- `AssetResource` for managing assets (list, get, delete).
- `ComponentResource` for managing components (list, get, delete).
- `RepositoryResource` for managing repositories (list, get, delete, invalidate cache, rebuild index).
- `SearchResource` for flexible component and asset search.
- `SystemResource` for health and status checks.
- Laravel integration documentation.
- Comprehensive PHPDoc documentation for all methods.
- 100% test coverage with PHPUnit.
- Static analysis configuration with PHPStan.

### Security
- Hardened default HTTP client settings (timeout, connect_timeout, verify).
- Improved URL normalization to prevent duplicate path segments.
- Enhanced header merging to ensure mandatory headers are preserved.

