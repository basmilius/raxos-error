<?php
declare(strict_types=1);

namespace Raxos\Error;

/**
 * Class InvalidArgumentException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Error
 * @since 2.0.0
 */
final class InvalidArgumentException extends Exception
{

    /**
     * InvalidArgumentException constructor.
     *
     * @param string $message
     *
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public function __construct(string $message)
    {
        parent::__construct(
            'invalid_argument',
            $message
        );
    }

}
