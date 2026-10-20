<?php
require_once __DIR__ . '/classes/Libro.php';
require_once __DIR__ . '/classes/Revista.php';
require_once __DIR__ . '/classes/DVD.php';

$catalogo = [
    new Libro('El Quijote', 'Cervantes', 1605, '978-8420412146'),
    new Revista('National Geographic', 'NatGeo', 2024, 245),
    new DVD('El Padrino', 'Coppola', 1972, 175),
];

foreach ($catalogo as $material) {
    echo $material->mostrarFicha(), '<br>';
}