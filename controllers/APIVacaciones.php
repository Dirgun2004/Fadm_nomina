<?php

namespace Controllers;

use Model\Empleado;
use Model\Vacaciones;

class APIVacaciones {

    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vacaciones = new Vacaciones($_POST);
            $alertas = $vacaciones->validar();
            if(empty($alertas)){
                $resultado = $vacaciones->guardar();
                
                // Respondemos en JSON indicando éxito
                echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'Vacaciones registradas correctamente.'
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

    public static function obtenerDiasEmpleado() {
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['empleado_id'])) {
        $empleadoId = filter_var($_GET['empleado_id'], FILTER_VALIDATE_INT);
        
        if(!$empleadoId) {
            echo json_encode(['error' => 'ID inválido']);
            return;
        }

        $empleado = Empleado::find($empleadoId);
        if(!$empleado || empty($empleado->ingreso)) {
            echo json_encode(['error' => 'Empleado no encontrado']);
            return;
        }

        // Calculamos los días por antigüedad con la función que ya tienes
        $diasDeVacacion = calcularDiasVacacionesPorAntiguedad($empleado->ingreso);
        $vacacionesAnteriores = Vacaciones::where('empleado_id', $empleado->id);
        $saldoAnterior = !empty($vacacionesAnteriores) ? intval($vacacionesAnteriores->dias_restantes) : 0;
        $diasDisponiblesTotales = $diasDeVacacion['dias_disponibles'] + $saldoAnterior;
            

        // Devolvemos la respuesta en JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'dias_disponibles' => $diasDisponiblesTotales,
            'anos_servicio' => $diasDeVacacion['anos_servicio']
        ]);
        return;
    }
}
}