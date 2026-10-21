<?php

namespace Controllers;

use Model\FaltasEmpleados;

class APIDeduccionController {

    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deduccion = new FaltasEmpleados($_POST);
            $alertas = $deduccion->validar();
            if(empty($alertas)){
                $resultado = $deduccion->guardar();
                
                // Respondemos en JSON indicando éxito
                echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'Deducciones registradas correctamente.'
                ]);
                return;
            } else {
                // Si hay errores de validación
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'Por favor, completa los campos obligatorios.'
                ]);
                return;
            }
        }
    }
}