<?php

namespace CTG\Exceptions;

use Exception;
use Throwable;

/**
 * Class CannotAccessAwsException
 *
 * @package CTG\Exceptions
 */
class CannotAccessAwsException extends Exception
{
    /**
     * CannotAccessAwsException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}