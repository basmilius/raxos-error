<?php
declare(strict_types=1);

use Raxos\Error\InvalidArgumentException;

covers(InvalidArgumentException::class);

it('preserves argument details in the exception and public error payload', function (string $message): void {
    $error = new InvalidArgumentException($message);
    expect($error->getMessage())->toBe($message)->and($error->error)->toBe('invalid_argument')
        ->and(json_decode(json_encode($error, JSON_THROW_ON_ERROR), true)['error_description'])->toBe($message);
})->with(['', '0', 'Invalid café']);
