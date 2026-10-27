<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Includes/exceptions.php';

use App\Exceptions\MaterialNoEncontradoException;
use App\Repos\MaterialRepo;
use function App\Config\db;
use App\Includes\auth;

requireLogin();

$id = (int)($_GET['id'] ?? 0);

try {
    $repo = new MaterialRepo();
    $material = $repo->findById($id);
} catch (MaterialNoEncontradoException $e) {
    http_response_code(404);
    require __DIR__ . '/../app/Includes/header.php';
    echo '<h1>Material no encontrado</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<a href="/index.php">Volver al catalogo</a>';
    require __DIR__ . '/../app/Includes/footer.php';
    exit; 
}

require __DIR__ . '/../app/Includes/header.php';

$stmt = db()->prepare(
    'SELECT id
     FROM prestamos
     WHERE material_id = ?
       AND usuario_id = ?
       AND fecha_devolucion IS NULL
     LIMIT 1'
);
$stmt->execute([$id, currentUser()['id']]);
$tienePrestamo = (bool)$stmt->fetch();

require __DIR__ . '/../app/Includes/header.php';

?>
<h1><?= htmlspecialchars($material->mostrarFicha()) ?></h1>

<dl>
  <dt>Tipo</dt><dd><?= htmlspecialchars($material->tipoMaterial()) ?></dd>
  <dt>Autor</dt><dd><?= htmlspecialchars($material->getAutor()) ?></dd>
  <dt>Año</dt><dd><?= $material->getAnio() ?? '—' ?></dd>
  <dt>Antigüedad</dt><dd><?= $material->antiguedad() ?> años</dd>
  <dt>Días de préstamo</dt><dd><?= $material->diasPrestamo() ?></dd>
  <dt>Añadido al catálogo</dt><dd><?= $material->fechaFormateada() ?></dd>
  <dt>Estado</dt><dd><?= $material->isDisponible() ? 'Disponible' : 'Prestado' ?></dd>
</dl>

<?php if ($material->isDisponible()): ?>
    <form method="POST" action="prestar.php">
        <input type="hidden" name="material_id" value="<?= $material->getId() ?>">
        <button type="submit">Pedir prestado</button>
    </form>
<?php else: ?>
    <form method="POST" action="devolver.php">
      <input type="hidden" name="material_id" value="<?= $material->getId() ?>">
      <button type="submit">Devolver material</button>
</form>
<?php endif; ?>

<?php require __DIR__ . '/../app/Includes/footer.php'; ?>
