<?php

declare(strict_types=1);

namespace App\Classes;

use App\Classes\Traits\Loggable;
use App\Classes\Traits\TieneFechas;

class MaterialBiblioteca implements Prestable
{
    use TieneFechas;
    use Loggable;

    private int $id;
    private string $titulo;
    private string $autor;
    private ?int $anio;
    private bool $disponible;

    public function __construct(
        string $titulo,
        string $autor,
        ?int $anio = null,
        bool $disponible = true,
        int $id = 0
    ) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->anio = $anio;
        $this->disponible = $disponible;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getAnio(): ?int
    {
        return $this->anio;
    }

    public function isDisponible(): bool
    {
        return $this->disponible;
    }

    public function setTitulo(string $t): void
    {
        $this->titulo = $t;
    }

    public function setDisponible(bool $d): void
    {
        $this->disponible = $d;
    }

    public function mostrarFicha(): string
    {
        $anio = $this->anio ?? 's/f';

        return sprintf(
            '[%s] %s — %s (%s)',
            $this->tipoMaterial(),
            $this->titulo,
            $this->autor,
            $anio
        );
    }

    public function tipoMaterial(): string
    {
        return 'material';
    }

    public function antiguedad(): int
    {
        if ($this->anio === null) {
            return 0;
        }

        return (int) date('Y') - $this->anio;
    }

    public function esAntiguo(): bool
    {
        return $this->antiguedad() > 50;
    }

    public function setAnio(?int $a): void
    {
        $this->anio = $a;
    }

    public function setAutor(string $a): void
    {
        $this->autor = $a;
    }

    public function resumen(): string
    {
        return $this->tipoMaterial() . ':' . $this->titulo;
    }

    public function prestar(): bool
    {
        if (!$this->disponible) {
            $this->log("Intento fallido de prestamo: {$this->titulo}");
            return false;
        }

        $this->disponible = false;
        $this->log("Prestamo realizado: {$this->titulo}");

        return true;
    }

    public function devolver(): void
    {
        $this->disponible = true;
    }

    public function puedePrestarse(): bool
    {
        return $this->disponible
            && $this->anio !== null
            && $this->anio < 2010;
    }

    public function diasPrestamo(): int
    {
        return 15;
    }
}
