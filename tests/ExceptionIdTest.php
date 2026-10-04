<?php
declare(strict_types=1);

use Raxos\Error\ExceptionId;
use RaxosTests\Error\UnitIdCaller;
use function RaxosTests\Error\unitFunctionId;

covers(ExceptionId::class);

it('serializes explicit identifiers as numbers and produces stable name-specific identifiers', function (): void {
    expect(json_encode(new ExceptionId(0), JSON_THROW_ON_ERROR))->toBe('0')
        ->and(ExceptionId::for('alpha')->value)->toBe(ExceptionId::for('alpha')->value)
        ->and(ExceptionId::for('alpha')->value)->not->toBe(ExceptionId::for('beta')->value);
});

it('infers function, static-method and instance-method identities from the caller', function (): void {
    expect(unitFunctionId()->value)->toBe(ExceptionId::for('RaxosTests\\Error\\unitFunctionId')->value)
        ->and(UnitIdCaller::staticId()->value)->toBe(ExceptionId::for(UnitIdCaller::class . '::staticId')->value)
        ->and(new UnitIdCaller()->instanceId()->value)->toBe(ExceptionId::for(UnitIdCaller::class . '->instanceId')->value);
});
