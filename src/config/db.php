<?php
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=mariadb;dbname=lamp_db;charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, 'lamp_user', 'lamp_pass', [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log('DB error: ' . $e->getMessage());
            http_response_code(500);
            exit('Error de conexión a la base de datos. Revisa que el contenedor "mariadb" esté levantado.');
        }
    }
    return $pdo;
}