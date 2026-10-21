<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>MiniApp Tareas</title>
</head>
<body>
<nav>
  <?php if (currentUser()): ?>
    <a href="/public/index.php">Mis tareas</a>
    <?php if (currentUser()['rol'] === 'admin'): ?>
      | <a href="/public/admin.php">Panel admin</a>
    <?php endif; ?>
    | <span><?= htmlspecialchars(currentUser()['email']) ?></span>
    | <a href="/public/logout.php">Salir</a>
  <?php else: ?>
    <a href="/public/login.php">Entrar</a>
  <?php endif; ?>
</nav>
<main>