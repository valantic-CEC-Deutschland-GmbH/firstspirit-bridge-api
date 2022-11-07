<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Exception;

use Exception;

class FirstSpiritApiDispatchingException extends Exception
{
    /**
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param \Exception|null $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = '', $code = 404, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
