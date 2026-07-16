# Changelog

All notable changes to `fanmade/laravel-bitwise-trait` are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased] — v3.0.0

Modernization release. The trait's public API (`getFlag`, `setFlag`, `toggleFlag`)
is **unchanged** — upgrading is only about the supported PHP and tooling versions.

### Changed
- **Requires PHP 8.2+** (was 7.4+).
- Bumped the PHPUnit dev dependency to `^11.5 || ^12.0` (was `^9.5`) and updated
  `phpunit.xml` to the current schema.

### Removed
- Dropped the hardcoded `version` field from `composer.json`; the version now comes
  from the Git tag, which is the Composer-recommended practice.

### Upgrade guide

The trait's method signatures did not change, so **no code changes are required** as
long as you're on PHP 8.2 or newer.

1. Ensure your application runs on **PHP 8.2+**.
2. Update the constraint:
   ```bash
   composer require fanmade/laravel-bitwise-trait:^3.0
   ```
3. If you still document flags with the old magic-method accessors
   (`getXAttribute`/`setXAttribute`) or `scopeX` methods, they continue to work.
   The [README](README.md) shows the modern `Attribute` and `#[Scope]` equivalents
   when you're ready to adopt them.

Still on PHP < 8.2? Stay on `v2.*` (PHP 7.4+) or `v1.*` (older).

## [2.0.1] — 2021-11

### Changed
- Adjusted the return values of the trait methods.

### Fixed
- Corrected the PHP requirement in the README and improved the examples.

## [2.0.0]

### Added
- Basic PHPUnit test coverage.

### Changed
- General code-quality improvements.

## [1.0.1]

- Maintenance release.

## [1.0.0]

- Initial release.
