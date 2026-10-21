<?php
trait Loggable {
    public function log(string $mensaje): void {
        error_log(sprintf('[%s] %s: %s',
            date('Y-m-d H:i:s'),
            static::class,
            $mensaje
        ));
    }
}