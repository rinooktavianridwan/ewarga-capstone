<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;

class FlowException extends Exception implements ClientAware
{
    /**
     * Returns true when exception message is safe to be displayed to a client.
     *
     * @return bool
     * @api
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'flow-exception';
    }
}
