<?php

declare(strict_types=1);

namespace App\Repos;

require_once __DIR__ . '/../app/Includes/auth.php';
requireLogin();
require_once __DIR__ . '/../app/Repos/MaterialRepo.php';
require_once __DIR__ . '/../app/Includes/exceptions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['material_id'] ?? 0);
$userId = (int)currentUser()['id'];

$repo = new MaterialRepo();
$material = $repo->findById($id);

if (!$material) {
    http_response_code(404);
    exit('Material no encontrado');
}


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
$prestamo = $stmt->fetch();

if (!$prestamo) {
    http_response_code(403);
    exit('No puedes devolver este material porque no lo tienes prestado.');
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
