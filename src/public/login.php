<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Includes/auth.php';

require_once __DIR__ . '/../app/Includes/exceptions.php';

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($email === '') {
        $error = 'El email es obligatorio.';
    } elseif ($pass === '') {
        $error = 'La contraseña es obligatoria.';
    } elseif (strlen($pass) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif (login($email, $pass)) {
        if (!empty($_POST['recordarme'])) {
            setcookie('ultimo_email', $email, [
                'expires'  => time() + 60 * 60 * 24 * 30,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }
        header('Location: index.php');
        exit;
    } else {
        $error = 'Credenciales incorrectas.';
    }
}

require __DIR__ . '/../app/Includes/header.php';
?>
<h1>Iniciar sesión</h1>

<?php if ($error): ?>
  <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="login.php" novalidate>
  <label>Email
    <input type="email" name="email" required
           value="<?= htmlspecialchars($email ?: ($_COOKIE['ultimo_email'] ?? '')) ?>">
  </label>

  <label>Contraseña
    <input type="password" name="password" required minlength="6">
  </label>

  <label>
    <input type="checkbox" name="recordarme" value="1"> Recordarme
  </label>

  <button type="submit">Entrar</button>
</form>

<?php require __DIR__ . '/../app/Includes/footer.php'; ?>