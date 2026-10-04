<?php
declare(strict_types=1);

namespace RaxosTests\Error;

use Raxos\Error\{Exception, ExceptionId};

enum UnitErrorCode: int
{

    case CUSTOM = 42;

}

final class UnitError extends Exception {}

final class UnitIdCaller
{

    public static function staticId(): ExceptionId
    {
        return ExceptionId::guess();
    }

    public function instanceId(): ExceptionId
    {
        return ExceptionId::guess();
    }

}

function unitFunctionId(): ExceptionId
{
    return ExceptionId::guess();
}
