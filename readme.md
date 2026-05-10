# Undescribed Tests

A wrapper for PestPHP's `test()` function that allows creating tests without providing a description. When omitted, the description is automatically generated using the file name and line number where the closure is defined.

## Installation

```bash
composer require andaniel05/undescribed-tests
```

## Usage

### Omitting description

When passing only a closure as argument, the description is automatically generated:

```php
<?php

use Andaniel05\UndescribedTests\Facade;

Facade::test(function () {
    expect(true)->toBe(true);
});
```

Expected output:
```
✓ tests/Unit/FacadeTest.php:5
```

### Using empty string

You can also pass an empty string explicitly:

```php
Facade::test('', function () {
    expect(true)->toBe(true);
});
```

### Explicit description

If you prefer, you can still use a manual description like in regular Pest:

```php
Facade::test('my described test', function () {
    expect(true)->toBe(true);
});
```

### Recommended approach: create a custom wrapper (ideal)

Instead of calling `Facade::test()` directly throughout your tests, create a wrapper function in your `tests/Pest.php`:

```php
function _test(string|Closure|null $description = null, ?Closure $closure = null): HigherOrderTapProxy|TestCall
{
    return Facade::test($description, $closure);
}
```

Then use it in your test files:

```php
_test(function () {
    expect(true)->toBe(true);
});
```

>The package [andaniel05/test-function](https://github.com/andaniel05/test-function) contains an installable `_test` wrapper.

### Groups

When the description is omitted, the test is automatically assigned to the `undescribed` group (or any other you specify). This allows easy filtering of tests without explicit description:

```php
Facade::test(function () {
    expect(true)->toBe(true);
}); // Assigned to 'undescribed' group

Facade::test('', function () {
    expect(true)->toBe(true);
}, 'my-group'); // Assigned to 'my-group' group
```

When an explicit description is provided, no group is automatically assigned.

## API

### `Facade::test()`

```php
public static function test(
    string|Closure|null $description = null,
    ?Closure $closure = null,
    ?string $undescribedGroupName = 'undescribed',
): HigherOrderTapProxy|TestCall
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `description` | `string\|Closure\|null` | Test description or closure (if description is omitted) |
| `closure` | `Closure\|null` | Closure with assertions |
| `undescribedGroupName` | `string\|null` | Group for tests without description (default: `undescribed`) |

## How it works

Internally, the `Facade` class uses `ReflectionFunction` to inspect the closure and extract:

- The file path where it is defined
- The line number where the closure starts

With this information, it builds an automatic description with the format: `relative/path/file.php:line_number`

## Running tests

```bash
./vendor/bin/pest
```

### Filter by group

```bash
./vendor/bin/pest --group=undescribed
```