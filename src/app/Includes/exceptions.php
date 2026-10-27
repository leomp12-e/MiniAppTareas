<?php
declare(strict_types=1);

use App\Exceptions\MaterialNoEncontradoException;

set_exception_handler(function (Throwable $e): void {
    error_log('Excepcion no capturada: ' . $e->getMessage());
    error_log($e->getTraceAsString());

    //Decide el codigo HTTP
    http_response_code(
        $e instanceof MaterialNoEncontradoException ? 404 : 500
    );

    //Muestra un mensaje generico al usuario
    require __DIR__ . '/header.php';
    echo '<h1>Algo ha ido mal</h1>';
    echo '<p>Intentalo mas tarde</p>';
    require __DIR__ . '/footer.php';
    exit;
});