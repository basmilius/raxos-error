<?php
declare(strict_types=1);

use Raxos\Contract\ExceptionInterface;
use RaxosTests\Error\TestFailure;

it('preserves the previous exception and exposes a stable error payload', function (): void {
    $cause = new RuntimeException('cause');
    $exception = new TestFailure('Invalid input.', previous: $cause);
    expect($exception)->toBeInstanceOf(ExceptionInterface::class);
    expect($exception->getPrevious())->toBe($cause);
    $payload = $exception->jsonSerialize();
    expect($payload['error_description'])->toBe('Invalid input.');
    expect($payload['code'])->toBe(new TestFailure('Other input.')->getCode());
    expect($payload)->not->toHaveKey('previous');
});

it('preserves nested Raxos errors and stable identifiers in JSON', function (): void {
    $inner = new RaxosTests\Error\TestFailure('inner');
    $outer = new RaxosTests\Error\TestFailure('outer', $inner);
    $data = json_decode(json_encode($outer, JSON_THROW_ON_ERROR), true);
    expect($data['previous']['error_description'])->toBe('inner')
        ->and($outer->getPrevious())->toBe($inner)
        ->and($outer->getCode())->toBe($inner->getCode())
        ->and(Raxos\Error\ExceptionId::for('different')->value)->not->toBe($inner->getCode());
});

it('keeps invalid argument errors usable as native exceptions and JSON responses', function (): void {
    $error = new Raxos\Error\InvalidArgumentException('bad input');
    expect($error->getMessage())->toBe('bad input')->and($error)->toBeInstanceOf(Throwable::class)
        ->and(json_decode(json_encode($error), true)['error_description'])->toBe('bad input');
});
