<?php
declare(strict_types=1);

namespace App\Repos;

use App\Classes\Mapa;
use App\Classes\DVD;
use App\Classes\Libro;
use App\Classes\MaterialBiblioteca;
use App\Classes\Revista;
use function App\Config\db;
use App\Exceptions\MaterialNoEncontradoException;


class MaterialRepo {

    public function findAll(): array {
        $stmt = db()->query('SELECT * FROM materiales ORDER BY tipo, titulo');
        $materiales = [];
        foreach ($stmt->fetchAll() as $fila) {
            $materiales[] = $this->hidratar($fila);
        }
        return $materiales;
    }

    public function findById(int $id): MaterialBiblioteca {
        $stmt = db()->prepare('SELECT * FROM materiales WHERE id = ?');
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
       
        if($fila === false) {
            throw new MaterialNoEncontradoException($id);
        }
        return $this->hidratar($fila);
    }

    public function findDisponibles(): array {
        $stmt = db()->query('SELECT * FROM materiales WHERE disponible = 1 ORDER BY titulo');
        $out = [];
        foreach($stmt->fetchAll() as $fila){
            $out[] = $this->hidratar($fila);
        }
        return $out;
    }

    public function findByTipo(string $tipo): array {
        $stmt = db()->prepare('SELECT * FROM materiales WHERE tipo = ? ORDER BY titulo');
        $stmt->execute([$tipo]);
        $out=[];
        foreach ($stmt->fetchAll() as $fila) {
            $out[] = $this->hidratar($fila);
        }
        return $out;
    }

    public function contarPorTipo(): array {
        $stmt = db()->query('SELECT tipo, COUNT(*) AS total FROM materiales GROUP BY tipo');
        $out = [];
        foreach ($stmt->fetchAll() as $fila) {
            $out[$fila['tipo']] = (int)$fila['total'];
        }
        return $out;
    }

    /**
     * Convierte una fila de BBDD en el objeto PHP correcto.
     * También hidrata la fecha de creación desde 'creado_en'.
     */
    private function hidratar(array $fila): MaterialBiblioteca {
        $material = match ($fila['tipo']) {
            'libro'   => new Libro(
                $fila['titulo'], $fila['autor'], (int)$fila['anio'],
                $fila['isbn'] ?? '', (bool)$fila['disponible'], (int)$fila['id']
            ),
            'revista' => new Revista(
                $fila['titulo'], $fila['autor'], (int)$fila['anio'],
                (int)$fila['numero'], (bool)$fila['disponible'], (int)$fila['id']
            ),
            'dvd'     => new DVD(
                $fila['titulo'], $fila['autor'], (int)$fila['anio'],
                (int)$fila['duracion_min'], (bool)$fila['disponible'], (int)$fila['id']
            ),
            'mapa'  => new Mapa(
                $fila['titulo'],
                $fila['autor'],
                $fila['anio'] !== null ? (int)$fila['anio'] : null,
                (bool)$fila['disponible'],
                (int)$fila['id']
            ),
            default   => throw new RuntimeException("Tipo desconocido: {$fila['tipo']}"),
        };
        $material->setFechaCreacion($fila['creado_en'] ?? null);
        return $material;
    }

    public function setDisponible(int $id, bool $disponible): void {
        $stmt = db()->prepare('UPDATE materiales SET disponible = ? WHERE id = ?');
        $stmt->execute([$disponible ? 1 : 0, $id]);
    }

    public function registrarPrestamo(int $materialId, int $usuarioId): void {
        $stmt = db()->prepare(
            'INSERT INTO prestamos (material_id, usuario_id) VALUES (?, ?)'
        );
        $stmt->execute([$materialId, $usuarioId]);
    }

    public function crear(MaterialBiblioteca $m): int {
    $tipo = match (true) {
        $m instanceof Libro   => 'libro',
        $m instanceof Revista => 'revista',
        $m instanceof DVD     => 'dvd',
        default               => throw new InvalidArgumentException('Tipo no soportado'),
    };

    $isbn   = $m instanceof Libro   ? $m->getIsbn()        : null;
    $numero = $m instanceof Revista ? $m->getNumero()      : null;
    $dur    = $m instanceof DVD     ? $m->getDuracionMin() : null;

    $stmt = db()->prepare(
        'INSERT INTO materiales (tipo, titulo, autor, anio, isbn, numero, duracion_min, disponible)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $tipo, $m->getTitulo(), $m->getAutor(), $m->getAnio(),
        $isbn, $numero, $dur, $m->isDisponible() ? 1 : 0
    ]);
    return (int)db()->lastInsertId();
}
}