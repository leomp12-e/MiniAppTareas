<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\MaterialNoDisponibleException;
use App\Repos\MaterialRepo;

class PrestamoService
{
    public function __construct(private MaterialRepo $repo) {}

    /**
     * Intenta prestar un material a un usuario.
     *
     * @throws MaterialNoDisponibleException si el material ya está prestado
     * @throws \App\Exceptions\MaterialNoEncontradoException si el id no existe
     */
    public function prestar(int $materialId, int $usuarioId): void
    {
        $material = $this->repo->findById($materialId);
    try {
        if (!$material->prestar()) {
            throw new MaterialNoDisponibleException($material->getTitulo());
        }

        $this->repo->setDisponible($materialId, false);
        $this->repo->registrarPrestamo($materialId, $usuarioId);
    } finally {
        $material->log(
            "Préstamo de '{$material->getTitulo()}' al usuario {$usuarioId}"
            );
        }
    }
}