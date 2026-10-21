<?php 
use Model\ActiveRecord; // o ActiveRecord según lo tengas escrito

// Ruta absoluta directa a la raíz de tu proyecto en el servidor
$raiz_servidor = '/home3/fayduran/repositories/Fadm_nomina';

if (file_exists($raiz_servidor . '/vendor/autoload.php')) {
    require $raiz_servidor . '/vendor/autoload.php';
} else {
    // Por si acaso corre en local buscando un nivel arriba
    require __DIR__ . '/../vendor/autoload.php';
}

$dotenv = Dotenv\Dotenv::createImmutable($raiz_servidor);
$dotenv->load();

require $raiz_servidor . '/includes/funciones.php';
require $raiz_servidor . '/includes/database.php';

// Conectarnos a la base de datos
ActiveRecord::setDB($db);