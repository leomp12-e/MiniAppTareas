<?php
declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class MaterialNoDisponibleException extends RuntimeException
{
    public function __construct(string $titulo)
    {
        parent::__construct("El material '{$titulo}' no esta disponible para préstamo");
    }
}