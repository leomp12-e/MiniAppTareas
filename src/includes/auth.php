<?php
// includes/auth.php — VERSIÓN REAL (sustituye al stub del Bloque 1)

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'path'     => '/',
]);
session_start();

require_once __DIR__ . '/../config/db.php';

function login(string $email, string $pass): bool {
    $stmt = db()->prepare('SELECT id, email, password_hash, rol FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if ($u && password_verify($pass, $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'    => (int)$u['id'],
            'email' => $u['email'],
            'rol'   => $u['rol'],
        ];
        return true;
    }
    return false;
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function requireLogin(): void {
    if (!currentUser()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin(): void {
    requireLogin();
    if (currentUser()['rol'] !== 'admin') {
        http_response_code(403);
        exit('Acceso denegado: se requiere rol administrador.');
    }
}

function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
                  $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}