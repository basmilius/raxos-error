<?php
declare(strict_types=1);

namespace RaxosTests\Error;

use Raxos\Error\Exception;
use Throwable;

final class TestFailure extends Exception
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct('test_failure', $message, previous: $previous);
    }
}
