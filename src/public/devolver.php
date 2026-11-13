<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Includes/exceptions.php';
require_once __DIR__ . '/../app/Includes/auth.php';

use App\Repos\MaterialRepo;
use function App\Config\db;

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['material_id'] ?? 0);
$userId = (int) currentUser()['id'];

try {
    $repo = new MaterialRepo();
    $material = $repo->findById($id);

    $stmt = db()->prepare(
        'SELECT id
         FROM prestamos
         WHERE material_id = ?
         AND usuario_id = ?
         AND fecha_devolucion IS NULL
         ORDER BY id DESC
         LIMIT 1'
    );

    $stmt->execute([$id, $userId]);
    $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prestamo) {
        exit('No tienes este material prestado.');
    }

    $material->devolver();
    $repo->setDisponible($id, true);

    $stmt = db()->prepare(
        'UPDATE prestamos
         SET fecha_devolucion = NOW()
         WHERE id = ?'
    );

    $stmt->execute([$prestamo['id']]);

    header('Location: material.php?id=' . $id);
    exit;
} catch (Throwable $e) {
    http_response_code(500);

    echo '<h1>Error al devolver</h1>';
    echo '<p>' . htmlspecialchars(
        $e->getMessage(),
        ENT_QUOTES,
        'UTF-8'
    ) . '</p>';

    echo '<p>Línea: ' . $e->getLine() . '</p>';
}
