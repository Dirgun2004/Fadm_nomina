<?php

namespace Controllers;

use Model\Feriado; // Asumiendo que creaste tu modelo Feriado
use MVC\Router;

class APIFeriadoController {

    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Leer los datos JSON enviados por el fetch de JavaScript
            $datos = json_decode(file_get_contents('php://input'), true);

            // 2. Instanciar el modelo con los datos recibidos
            $feriado = new Feriado($datos);

            // 3. Guardar en la base de datos usando tu ActiveRecord
            $resultado = $feriado->guardar();

            // 4. Responder al navegador en formato JSON
            header('Content-Type: application/json');
            if($resultado) {
                echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'Feriado registrado correctamente'
                ]);
            } else {
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'No se pudo guardar el feriado'
                ]);
            }
            exit;
        }
    }
}