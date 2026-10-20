<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once __DIR__ . '/repos/MaterialRepo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['material_id'] ?? 0);
$repo = new MaterialRepo();
$material = $repo->findById($id);

if (!$material) {
    http_response_code(404);
    exit('Material no encontrado');
}

if ($material->prestar()) {
    $repo->setDisponible($id, false);
    $repo->registrarPrestamo($id, currentUser()['id']);
    $material->log("Préstamo de '{$material->getTitulo()}' a " . currentUser()['email']);
}

header('Location: material.php?id=' . $id);
exit;