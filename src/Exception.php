<?php
declare(strict_types=1);

namespace Raxos\Error;

use BackedEnum;
use Exception as NativeException;
use JsonSerializable;
use Raxos\Contract\ExceptionInterface;
use Throwable;

/**
 * Class Exception
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Error
 * @since 2.0.0
 */
abstract class Exception extends NativeException implements ExceptionInterface
{

    /**
     * Exception constructor.
     *
     * @param string $error
     * @param string $errorDescription
     * @param BackedEnum|ExceptionId|null $code
     * @param Throwable|null $previous
     *
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public function __construct(
        public readonly string $error,
        public readonly string $errorDescription,
        BackedEnum|ExceptionId|null $code = null,
        public readonly ?Throwable $previous = null
    )
    {
        $code ??= ExceptionId::for(static::class);

        parent::__construct($this->errorDescription, $code->value, $this->previous);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public function jsonSerialize(): array
    {
        $result = [
            'code' => $this->code,
            'error' => $this->error,
            'error_description' => $this->errorDescription
        ];

        if ($this->previous instanceof JsonSerializable) {
            $result['previous'] = $this->previous;
        }

        return $result;
    }

}
