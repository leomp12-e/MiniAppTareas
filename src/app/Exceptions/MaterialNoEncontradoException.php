<?php
declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class MaterialNoEncontradoException extends RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("No existe un material con id {$id}");
    }
}