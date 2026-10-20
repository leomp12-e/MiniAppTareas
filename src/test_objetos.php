<?php
require_once __DIR__ . '/classes/Libro.php';
require_once __DIR__ . '/classes/Revista.php';
require_once __DIR__ . '/classes/DVD.php';

$catalogo = [
    new Libro('El Quijote', 'Cervantes', 1605, '978-8420412146'),
    new Libro('Cien años de soledad', 'García Márquez', 1967, '978-0307474728'),
    new Libro('1984', 'Orwell', 1949, '978-0451524935'),
    new Revista('NatGeo', 'NatGeo', 2024, 245),
    new Revista('Muy Interesante', 'Zinet', 2024, 512),
    new DVD('El Padrino', 'Coppola', 1972, 175),
    new DVD('Blade Runner', 'Scott', 1982, 117),
];

foreach ($catalogo as $c) {
    echo $c->tipoMaterial(), '→' , $c->mostrarFicha(), '<br>';
}
