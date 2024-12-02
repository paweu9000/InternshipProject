<?php

namespace App\Exception;

use Exception;

class BadBookException extends Exception
{
    private int $statusCode;

    public function __construct(string $message = "There was an error", int $statusCode = 400)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}