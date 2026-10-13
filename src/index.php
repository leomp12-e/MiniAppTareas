<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require __DIR__ . '/includes/header.php';

$stmt = db()->prepare('SELECT * FROM tareas WHERE usuario_id = ? ORDER BY creada_en DESC');
$stmt->execute([currentUser()['id']]);
$tareas = $stmt->fetchAll();
?>
<h1>Mis tareas</h1>

<form method="POST" action="tareas.php">
  <input type="text" name="titulo" maxlength="150" placeholder="Nueva tarea" required>
  <button>Añadir</button>
</form>

<ul>
<?php foreach ($tareas as $t): ?>
  <li>
    <form method="POST" action="tareas.php" style="display:inline">
      <input type="hidden" name="toggle_id" value="<?= $t['id'] ?>">
      <button type="submit"><?= $t['completada'] ? '☑' : '☐' ?></button>
    </form>
    <?= htmlspecialchars($t['titulo']) ?>
  </li>
<?php endforeach; ?>
</ul>

<?php require __DIR__ . '/includes/footer.php'; ?>