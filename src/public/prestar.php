<?php
require_once __DIR__ . '/../app/Includes/auth.php';
requireLogin();
require_once __DIR__ . '/../app/Repos/MaterialRepo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/index.php');
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

header('Location: /public/material.php?id=' . $id);
exit;