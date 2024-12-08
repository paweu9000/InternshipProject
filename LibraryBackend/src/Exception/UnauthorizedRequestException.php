<?php

namespace App\Exception;

use Exception;

class UnauthorizedRequestException extends Exception
{
    private int $statusCode;

    public function __construct(string $message = "Unauthorized access", int $statusCode = 401)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}