<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once __DIR__ . '/repos/MaterialRepo.php';

$repo = new MaterialRepo();
$materiales = $repo->findAll();

require __DIR__ . '/includes/header.php';
?>
<h1>Catálogo de la biblioteca</h1>

<ul>
<?php foreach ($materiales as $m): ?>
  <li>
    <?= htmlspecialchars($m->mostrarFicha()) ?>
    — <?= $m->isDisponible() ? '✅ Disponible' : '❌ Prestado' ?>
    · <a href="/material.php?id=<?= $m->getId() ?>">Ver ficha</a>
  </li>
<?php endforeach; ?>
</ul>

<?php require __DIR__ . '/includes/footer.php'; ?>