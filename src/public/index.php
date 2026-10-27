<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Includes/auth.php';
require_once __DIR__ . '/../app/Includes/exceptions.php';

use App\Repos\MaterialRepo;

requireLogin();

$repo = new MaterialRepo();
$materiales = $repo->findAll();

require __DIR__ . '/../app/Includes/header.php';
?>
<h1>Catálogo de la biblioteca</h1>

<ul>
<?php foreach ($materiales as $m): ?>
  <li>
    <?= htmlspecialchars($m->mostrarFicha()) ?>
    — <?= $m->isDisponible() ? '✅ Disponible' : '❌ Prestado' ?>
    · <a href="material.php?id=<?= $m->getId() ?>">Ver ficha</a>
  </li>
<?php endforeach; ?>
</ul>

<?php require __DIR__ . '/../app/Includes/footer.php'; ?>