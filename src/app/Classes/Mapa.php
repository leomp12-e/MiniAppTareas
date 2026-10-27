<?php
declare(strict_types=1);

namespace App\Classes;

class Mapa extends MaterialBiblioteca {

    public function __construct(
        string $titulo,
        string $autor,
        ?int $anio,
        bool $disponible = true,
        int $id = 0
    ) {
        parent::__construct($titulo, $autor, $anio, $disponible, $id);
    }

    public function tipoMaterial(): string {
        return 'Mapa';
    }
}
