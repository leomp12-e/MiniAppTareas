<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\LimitePrestamosException;
use App\Exceptions\MaterialNoDisponibleException;
use App\Repos\MaterialRepo;

class PrestamoService
{
    public function __construct(
        private MaterialRepo $repo
    ) {
    }

    /**
     * Intenta prestar un material a un usuario.
     *
     * @throws LimitePrestamosException Si el usuario ya tiene 3 préstamos activos.
     * @throws MaterialNoDisponibleException Si el material ya está prestado.
     * @throws \App\Exceptions\MaterialNoEncontradoException Si el id no existe.
     */
    public function prestar(int $materialId, int $usuarioId): void
    {
        if ($this->repo->contarPrestamosActivos($usuarioId) >= 3) {
            throw new LimitePrestamosException();
        }

        $material = $this->repo->findById($materialId);

        try {
            if (!$material->prestar()) {
                throw new MaterialNoDisponibleException(
                    $material->getTitulo()
                );
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
