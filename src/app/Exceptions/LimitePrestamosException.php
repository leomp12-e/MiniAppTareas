<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class LimitePrestamosException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'No puedes tener más de 3 préstamos activos.'
        );
    }
}
