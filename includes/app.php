<?php 
use Model\ActiveRecord;
if(file_exists(__DIR__ . '/../vendor/autoload.php')){
    require __DIR__ . '/../vendor/autoload.php';
}else{
    require __DIR__ . '/../repositories/Fadm_nomina/vendor/autoload.php';
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..'); // El '/..' es obligatorio para subir a la raíz
$dotenv->load();

require 'funciones.php';
require 'database.php';

// Conectarnos a la base de datos
ActiveRecord::setDB($db);