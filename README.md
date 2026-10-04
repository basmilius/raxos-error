<a href="https://bas.dev">
    <img src="https://bmcdn.nl/assets/branding/logo.svg" alt="Bas Milius" height="48" />
</a>

---

# Raxos Error

A common exception base with machine-readable error names, descriptions and numeric IDs.

[Documentation](https://raxos.dev/error/) | [Packagist](https://packagist.org/packages/raxos/error) | [Raxos](https://github.com/basmilius/raxos)

- Exceptions implement the shared `ExceptionInterface` and serialize to JSON.
- `ExceptionId` derives repeatable IDs from a class or method name.
- A shared `InvalidArgumentException` for invalid inputs.

## Installation

Requires PHP 8.5 or later. Composer checks the remaining package and extension dependencies declared in [composer.json](composer.json).

```sh
composer require "raxos/error:^3.3"
```

## Usage

```php
<?php
declare(strict_types=1);

use Raxos\Error\Exception;

require __DIR__ . '/vendor/autoload.php';

final class ProductNotFound extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct(
            error: 'product_not_found',
            errorDescription: "Product {$id} does not exist."
        );
    }
}

echo json_encode(new ProductNotFound(42));
```

The JSON representation contains `code`, `error` and `error_description`. If no code is supplied, the base exception derives one from the concrete exception class. A previous exception can be passed through the constructor.

## Documentation

- [Building custom exceptions](https://raxos.dev/error/custom-exceptions)

## Testing

Run this library's Pest suite from the Raxos workspace:

```sh
git clone --recurse-submodules https://github.com/basmilius/raxos.git
cd raxos
composer install
vendor/bin/pest --testsuite=error
```

See [Testing Raxos](https://github.com/basmilius/raxos/blob/main/TESTING.md) for PHP extensions, integration services and coverage commands. The library's [Tests workflow](.github/workflows/tests.yml) also runs in GitHub Actions.

## License

[MIT](LICENSE). Copyright (c) 2017 - present Bas Milius.
