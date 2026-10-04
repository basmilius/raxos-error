<?php
declare(strict_types=1);

use Raxos\Contract\ExceptionInterface;
use Raxos\Error\{Exception, ExceptionId, InvalidArgumentException};
use RaxosTests\Error\{TestFailure, UnitError, UnitErrorCode};

covers(Exception::class);

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
    $inner = new TestFailure('inner');
    $outer = new TestFailure('outer', $inner);
    $data = json_decode(json_encode($outer, JSON_THROW_ON_ERROR), true);
    expect($data['previous']['error_description'])->toBe('inner')
        ->and($outer->getPrevious())->toBe($inner)
        ->and($outer->getCode())->toBe($inner->getCode())
        ->and(ExceptionId::for('different')->value)->not->toBe($inner->getCode());
});

it('keeps invalid argument errors usable as native exceptions and JSON responses', function (): void {
    $error = new InvalidArgumentException('bad input');
    expect($error->getMessage())->toBe('bad input')->and($error)->toBeInstanceOf(Throwable::class)
        ->and(json_decode(json_encode($error), true)['error_description'])->toBe('bad input');
});

it('accepts explicit enum and numeric identifiers without exposing native exception traces', function (): void {
    foreach ([UnitErrorCode::CUSTOM, new ExceptionId(0)] as $code) {
        $error = new UnitError('unit', 'details', $code, new RuntimeException('cause'));
        expect($error->getCode())->toBe($code->value)->and($error->jsonSerialize())->toBe(['code' => $code->value, 'error' => 'unit', 'error_description' => 'details']);
    }
});
