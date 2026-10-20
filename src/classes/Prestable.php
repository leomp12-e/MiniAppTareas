<?php
interface Prestable {
    public function prestar(): bool;
    public function devolver(): void;
    public function diasPrestamo(): int;
    public function puedePrestarse(): bool;
}