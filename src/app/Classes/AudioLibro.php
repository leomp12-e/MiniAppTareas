<?php

declare(strict_types=1);

namespace App\Classes;

class AudioLibro extends Libro
{
    private int $duracionMin;

    public function __construct(
        string $titulo,
        string $autor,
        ?int $anio,
        string $isbn,
        int $duracionMin,
        bool $disponible = true,
        int $id = 0
    ) {
        parent::__construct(
            $titulo,
            $autor,
            $anio,
            $isbn,
            $disponible,
            $id
        );

        $this->duracionMin = $duracionMin;
    }

    public function getDuracionMin(): int
    {
        return $this->duracionMin;
    }

    public function tipoMaterial(): string
    {
        return 'AudioLibro';
    }

    public function mostrarFicha(): string
    {
        return parent::mostrarFicha() . ' · ' . $this->duracionMin . ' min';
    }
}
