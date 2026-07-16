# Laravel Bitwise Trait

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fanmade/laravel-bitwise-trait.svg)](https://packagist.org/packages/fanmade/laravel-bitwise-trait)
[![Total Downloads](https://img.shields.io/packagist/dt/fanmade/laravel-bitwise-trait.svg)](https://packagist.org/packages/fanmade/laravel-bitwise-trait)
[![License](https://img.shields.io/packagist/l/fanmade/laravel-bitwise-trait.svg)](LICENSE.md)

A tiny, framework-agnostic trait for storing many boolean flags in a **single integer column** using bitwise operations. One `tinyInteger` column can hold up to 8 independent true/false values instead of 8 separate columns.

Although it's built with Laravel in mind, the trait itself has **no framework dependency** — it works in any PHP class.

Inspired by the [PHP manual on bitwise operators](https://www.php.net/manual/en/language.operators.bitwise.php#108679) and Aaron Francis' write-up on [bitmasking in Laravel and MySQL](https://aaronfrancis.com/2021/bitmasking-in-laravel-and-mysql).

## Requirements

- PHP **8.2+**

> Using an older stack? `v2.*` supports PHP 7.4+, and `v1.*` supports even older versions. See the [upgrade notes](CHANGELOG.md) before moving to `v3`.

## Installation

```bash
composer require fanmade/laravel-bitwise-trait
```

No service provider registration is needed.

## How it works

Each flag is a single bit, so every flag's value is a distinct power of two (`1, 2, 4, 8, …`). A record's integer column is the bitwise OR of all the flags that are currently set. Because every flag owns its own bit, they never collide — you can read, set, or toggle one without touching the others.

You need one (ideally **unsigned**) integer column to store the flags. Pick its size based on how many flags you need — you only need one bit per flag:

```php
$table->unsignedTinyInteger('status');   // 1 byte  -> up to 8 flags
$table->unsignedSmallInteger('status');  // 2 bytes -> up to 16 flags
$table->unsignedMediumInteger('status'); // 3 bytes -> up to 24 flags
$table->unsignedInteger('status');       // 4 bytes -> up to 32 flags
```

Most of the time an `unsignedTinyInteger` is all you need. You can add as many flag columns as you like if you outgrow a single one.

## Quick start

Add the trait to your model and define one flag constant per bit. The clearest way to write the values is the shift syntax `1 << n`:

```php
<?php

namespace App\Models;

use Fanmade\Bitwise\BitwiseFlagTrait;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use BitwiseFlagTrait;

    public const SENT     = 1 << 0; // 1
    public const RECEIVED = 1 << 1; // 2
    public const SEEN     = 1 << 2; // 4
    public const READ     = 1 << 3; // 8
}
```

The trait gives you three **protected** helper methods to call from inside your model. The first argument is always the name of the column that stores the flags:

- `getFlag(string $column, int $flag): bool` — is the flag currently set?
- `setFlag(string $column, int $flag, bool $value): bool` — set or clear the flag; returns its new state
- `toggleFlag(string $column, int $flag): bool` — flip the flag; returns its new state

Because they're protected, you expose each flag through a small accessor (below) rather than calling them from outside the model.

## Exposing flags as attributes

Wrap each flag in an [accessor/mutator](https://laravel.com/docs/eloquent-mutators#accessors-and-mutators) so it reads and writes like a normal boolean attribute.

Since Laravel 9 the idiomatic way is a single `Attribute` method per flag:

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function seen(): Attribute
{
    return Attribute::make(
        get: fn (): bool => $this->getFlag('status', self::SEEN),
        set: fn (bool $value) => $this->setFlag('status', self::SEEN, $value),
    );
}
```

Now the flag behaves like any other attribute:

```php
$message->seen = true;
$message->seen; // => true
$message->save();
```

<details>
<summary>Older Laravel (magic-method accessors)</summary>

Before Laravel 9 you'd define the accessor and mutator as separate magic methods:

```php
public function getSeenAttribute(): bool
{
    return $this->getFlag('status', self::SEEN);
}

public function setSeenAttribute(bool $value): void
{
    $this->setFlag('status', self::SEEN, $value);
}
```
</details>

## Querying by flag (scopes)

To filter records by a flag, add a query scope. A bit is set when `column & flag = flag`, and unset when `column & flag = 0`.

Since Laravel 12 you can use the `#[Scope]` attribute, which drops the old `scope` method-name prefix:

```php
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

#[Scope]
protected function read(Builder $query): void
{
    $query->whereRaw('(status & ?) = ?', [self::READ, self::READ]);
}

#[Scope]
protected function unread(Builder $query): void
{
    $query->whereRaw('(status & ?) = 0', [self::READ]);
}
```

```php
Message::read()->get();
Message::unread()->get();
```

<details>
<summary>Older Laravel (scope-prefixed methods)</summary>

Before Laravel 12, name the method with a `scope` prefix instead of using the attribute:

```php
public function scopeRead(Builder $query): Builder
{
    return $query->whereRaw('(status & ?) = ?', [self::READ, self::READ]);
}

public function scopeUnread(Builder $query): Builder
{
    return $query->whereRaw('(status & ?) = 0', [self::READ]);
}
```
</details>

## Writing flag values

Beyond the shift syntax, you can define the constants however reads best to you — all three of these are equivalent:

```php
public const SENT = 8;         // decimal
public const SENT = 1 << 3;    // bit shift
public const SENT = 0b0001000; // binary literal
```

## Testing

```bash
composer install
vendor/bin/phpunit
```

## Credits

- [Fanmade](https://github.com/Fanmade)

## License

The MIT License (MIT). See [License File](LICENSE.md) for more information.
