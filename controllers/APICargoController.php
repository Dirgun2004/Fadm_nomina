<?php

namespace Controllers;

use Model\Cargo;
use MVC\Router;

class APICargoController {

    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $cargo = new Cargo($_POST);

            // 2. Guardamos en la base de datos usando tu método de ActiveRecord
            $resultado = $cargo->guardar();

            // 3. Preparamos la respuesta en JSON que espera tu JavaScript
            header('Content-Type: application/json');
            
            if($resultado) {
                echo json_encode([
                    'resultado' => true,
                    'mensaje' => 'Cargo agregado correctamente'
                ]);
            } else {
                echo json_encode([
                    'resultado' => false,
                    'mensaje' => 'No se pudo guardar el cargo'
                ]);
            }
            exit;
        }
    }
}