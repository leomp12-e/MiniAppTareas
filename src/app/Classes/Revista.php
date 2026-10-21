<?php
require_once __DIR__ . '/MaterialBiblioteca.php';

class Revista extends MaterialBiblioteca {
    private int $numero;

    public function __construct(
        string $titulo, string $autor, ?int $anio,
        int $numero, bool $disponible = true, int $id = 0
    ) {
        parent::__construct($titulo, $autor, $anio, $disponible, $id);
        $this->numero = $numero;
    }

    public function getNumero(): int { return $this->numero; }
    public function tipoMaterial(): string { return 'Revista'; }
    public function mostrarFicha(): string {
        return parent::mostrarFicha() . ' · Nº ' . $this->numero;
    }
}