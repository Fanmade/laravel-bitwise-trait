# Laravel Bitwise Trait
A simple trait to use bitwise operators on any class
Inspired by http://php.net/manual/de/language.operators.bitwise.php#108679

Updated after reading this blog post: https://aaronfrancis.com/2021/bitmasking-in-laravel-and-mysql

I just used it in Laravel so far, but you should be able to use it anywhere else with minor modifications.

## PHP Version
Version v2.* requires PHP 7.4+. If you're stuck to an older version, please use v1.* 

## Installation

You can install the package via composer:

```bash
composer require fanmade/laravel-bitwise-trait
```
That's all, no provider registration needed :)

## Usage

*This example is for a default Laravel (5.4) Model within the "App" namespace.*

You need an (ideally unsigned) integer field in your database which will store the properties.
The length does depend on the number of values you would like to store. You only need one bit per value, so it's 8 values for each byte, if the column is unsigned.

Examples (based on laravel migrations):
```php
$table->tinyInteger('status'); // 1 byte -> maximum of 7 different values
$table->unsignedTinyInteger('status'); // maximum of 8 different values
$table->smallInteger('status'); // 2 byte -> maximum of 16 different values
$table->unsignedSmallInteger('status'); // maximum of 17 different values
$table->mediumInteger('status'); // 3 byte -> maximum of 24 different values
```
You get the idea. Most times you probably only need an unsigned tinyInteger :)

There are only a few use-cases for more than one database field, but you can add as many fields as you like.

Include the Trait in your model like this:
```php
<?php 

namespace App;

use Fanmade\Bitwise\BitwiseFlagTrait;

class Message extends Model
{
  use BitwiseFlagTrait;
```

The best way to define your properties is via constants directly in the model.
You're of course free to use config variables or whatever you prefer.
```php
const MESSAGE_SENT     = 1; // BIT #1 of has the value 1
const MESSAGE_RECEIVED = 2; // BIT #2 of has the value 2
const MESSAGE_SEEN     = 4; // BIT #3 of has the value 4
const MESSAGE_READ     = 8; // BIT #4 of has the value 8
```

This alternative syntax may be easier to read:
```php
const MESSAGE_SENT     = 1 << 0;
const MESSAGE_RECEIVED = 1 << 1;
const MESSAGE_SEEN     = 1 << 2;
const MESSAGE_READ     = 1 << 3;
```

Or directly in binary notation:
```php
const MESSAGE_SENT     = 0b00000001;
const MESSAGE_RECEIVED = 0b00000010;
const MESSAGE_SEEN     = 0b00000100;
const MESSAGE_READ     = 0b00001000;
```

To set a property, just call the function like this:
```php
$this->setFlag('status', self::MESSAGE_SENT, true);
```

To get a property, just call the function like this:
```php
$sent = $this->getFlag('status', self::MESSAGE_SENT);
```
The first parameter *('status' in the example)* is always the column you set in the database.
Maybe you want to define that in a constant or variable.

To make your life easier, I recommend to use custom getters and setters.
```php
    public function setSentAttribute($sent = true): self
    {
        $this->setFlag('status', self::MESSAGE_SENT, $sent);
        
        return $this;
    }

    public function getSentAttribute(): bool
    {
        return $this->getFlag('status', self::MESSAGE_SENT);
    }

```

## Scopes
If you want to use the new field in scopes, you can do that like this:
```php
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeUnread($query)
    {
        return $query->whereRAW('NOT status & ' . self::MESSAGE_READ);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeRead($query)
    {
        return $query->where('status', '&', self::MESSAGE_READ);
    }

```
