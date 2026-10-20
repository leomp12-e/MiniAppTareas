<?php
require_once __DIR__ . '/MaterialBiblioteca.php';

class DVD extends MaterialBiblioteca {
    private int $duracionMin;

    public function __construct(
        string $titulo, string $autor, ?int $anio,
        int $duracionMin, bool $disponible = true, int $id = 0
    ) {
        parent::__construct($titulo, $autor, $anio, $disponible, $id);
        $this->duracionMin = $duracionMin;
    }

    public function getDuracionMin(): int { return $this->duracionMin; }
    public function tipoMaterial(): string { return 'DVD'; }
    public function mostrarFicha(): string {
        return parent::mostrarFicha() . ' · ' . $this->duracionMin . ' min';
    }

    public function diasPrestamo(): int {
        return 3;
    }

    public function puedePrestarse(): bool {
        if($this->disponible) 
            return true;
    }
}