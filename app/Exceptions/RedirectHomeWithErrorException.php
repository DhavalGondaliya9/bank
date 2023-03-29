<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class RedirectHomeWithErrorException extends Exception
{
    protected $message;

    public function __construct($message = null)
    {
        $this->message = $message ?: 'An error occurred';
    }

    public function render($request)
    {
        return to_route('home')->withErrors($this->message);
    }
}
