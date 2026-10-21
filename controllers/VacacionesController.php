<?php

namespace Controllers;

use Classes\Paginacion;
use Model\Cargo;
use Model\Empleado;
use Model\Empresa;
use Model\Feriado;
use Model\ParametrosLegales;
use Model\Sueldo_base;
use Model\Sueldo_minimo;
use Model\Sueldo_variable;
use Model\Vacaciones;
use MVC\Router;

class VacacionesController{
    public static function index(Router $router){
        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        $registro_por_pagina = 10;
        $idEmpresa = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($idEmpresa);
        $paginacion = '';

        if(!isset($_GET['page'])) {
            header("Location: /admin/vacaciones?id={$empresa->id}&page=1");
            return;
        }

        $db = Vacaciones::getDB();

        $query = $db->query("SELECT COUNT(*) as total 
          FROM vacaciones
          WHERE empresa_id = {$empresa->id} AND estatus_id = '1'");
        $resultadoActivos = $query->fetch_assoc();
        $total = (int) ($resultadoActivos['total'] ?? 0);

        $paginacion = new Paginacion($pagina_actual, $registro_por_pagina, $total);

        if($paginacion->total_paginas() > 0 && $paginacion->total_paginas() < $pagina_actual){
            header("Location: /admin/vacaciones?id={$empresa->id}&page=1");
            return;
        }

        $limit = $registro_por_pagina;
        $offset = $paginacion->offset();
        $vacaciones = Vacaciones::consultarSQL("SELECT * FROM vacaciones WHERE empresa_id = {$empresa->id} AND estatus_id = '1' ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}");
        
        foreach($vacaciones as $vacacion){
            $vacacion->empleado = Empleado::find($vacacion->empleado_id);
            $vacacion->cargo = Cargo::find($vacacion->cargoEmpleado_id);
        }

        $router->render('admin/vacaciones/index', [
            'titulo' => 'Vacaciones',
            'empresa' => $empresa,
            'paginacion' => $paginacion->paginacion(),
            'vacaciones' => $vacaciones
        ]);
    }

    public static function crear(Router $router){
        $alertas = [];
        $empresaId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $vacacion = new Vacaciones;
        $empleados = Empleado::whereArray(['empresa_id' => $empresaId]);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $_POST = array_map('trim', $_POST);
            $vacacion->sincronizar($_POST);
            $alertas = $vacacion->validar();
            if(empty($alertas)){
                $feriados = Feriado::all();
                $empleado = Empleado::find($_POST['empleado_id']);
                
                $sueldoActivo = Sueldo_base::where('empleado_id', $empleado->id);
                $sueldoMensual = $sueldoActivo ? floatval($sueldoActivo->sueldo) : 0;

                $arrayFeriados = array_column($feriados, 'fecha');

                $calculoDias = calcularPeriodoVacaciones($_POST['fecha_inicio'], $_POST['dias_solicitados'], $arrayFeriados);
                
                $vacacion->sincronizar($calculoDias);
                $vacacion->cargoEmpleado_id = $empleado->cargo_id;
                $vacacion->empresa_id = $empresaId;
                            
                $diasDeVacacion = calcularDiasVacacionesPorAntiguedad($empleado->ingreso);
                $vacacionesAnteriores = Vacaciones::where('empleado_id', $empleado->id);
                $saldoAnterior = !empty($vacacionesAnteriores) ? intval($vacacionesAnteriores->dias_restantes) : 0;
                $diasDisponiblesTotales = $diasDeVacacion['dias_disponibles'] + $saldoAnterior;
                $vacacion->dias_disponibles = $diasDisponiblesTotales;
                $vacacion->dias_adicionales = $diasDeVacacion['anos_servicio'];

                if ($vacacion->dias_solicitados > $vacacion->dias_disponibles) {
                    // Los días que rebasan el límite se van a anticipados
                    $vacacion->dias_anticipados = $vacacion->dias_solicitados - $vacacion->dias_disponibles;
                    $vacacion->dias_bonoAnticipado = $vacacion->dias_anticipados;
                    // Ya no quedan días disponibles en este periodo (todo se consumió y se pidió prestado)
                    $vacacion->dias_restantes = 0; 
                } else {
                    // Si pidió lo justo o menos, no hay días anticipados
                    $vacacion->dias_anticipados = 0;
                    $vacacion->dias_bonoAnticipado = $vacacion->dias_anticipados;
                    // Calculamos el saldo normal que le sobra
                    $vacacion->dias_restantes = $vacacion->dias_disponibles - $vacacion->dias_solicitados;
                }

                $salarioMinimo = Sueldo_minimo::get(1);
                $salarioMinimoActivo = array_shift($salarioMinimo);
                // Faov
                $faov = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'FAOV' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
                $faovActivo = !empty($faov) ? array_shift($faov) : null;
                // Ivss
                $ivss = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'IVSS' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
                $ivssActivo = !empty($ivss) ? array_shift($ivss) : null;
                // SPF
                $spf = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'SPF' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
                $spfActivo = !empty($spf) ? array_shift($spf) : null;
                
                
                $calculo = calcularVacaciones($sueldoMensual, $diasDeVacacion['anos_servicio'], $vacacion->dias_solicitados, $vacacion->dias_anticipados, $salarioMinimoActivo->sueldo, $faovActivo->porcentaje, $spfActivo->porcentaje, $ivssActivo->porcentaje, $calculoDias);
                $vacacion->sincronizar($calculo);
                
                $vacacion->sueldo_diario_promedio = $vacacion->sueldo_diario;
                $vacacion->sueldo_mensual_promedio = $vacacion->sueldo_mensual;
                $vacacion->sueldo_variable_mensual = 0;
                $vacacion->sueldo_variable_diario = 0;

                $vacacion->departamento_id = 1;
                
                $resultado = $vacacion->guardar();
                if($resultado){
                    header("Location: /admin/vacaciones?id={$empresaId}");
                }
            }
                
            }

        $router->render('admin/vacaciones/crear', [
            'titulo' => 'Crear Vacacion',
            'alertas' => $alertas,
            'empleados' => $empleados
        ]);
    }

    public static function personalizado(Router $router){
        $alertas = [];
        $vacacion = new Vacaciones;
        $empresaId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        $empleados = Empleado::whereArray(['empresa_id' => $empresaId]);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            $vacacion->sincronizar($_POST);
            $alertas = $vacacion->validar();
            if(empty($alertas)){
                $empleado = Empleado::find($_POST['empleado_id']);
                
                $db = Sueldo_variable::getDB();
                $fechaInicioVacaciones = $_POST['fecha_inicio']; // O la fecha que corresponda
                $empleadoId = $empleado->id;

                $query = "
                    SELECT AVG(total_mes) AS promedio 
                    FROM (
                        SELECT SUM(sueldo) AS total_mes 
                        FROM sueldo_variable 
                        WHERE empleado_id = {$empleadoId} 
                        AND fecha >= DATE_SUB('{$fechaInicioVacaciones}', INTERVAL 3 MONTH) 
                        AND fecha <= '{$fechaInicioVacaciones}'
                        GROUP BY DATE_FORMAT(fecha, '%Y-%m')
                    ) AS sueldos_mensuales
                ";

                $resultadoDb = $db->query($query);
                $registro = $resultadoDb ? $resultadoDb->fetch_assoc() : null;

                $sueldoVariablePromedio = $registro && $registro['promedio'] !== null ? floatval($registro['promedio']) : 0;

                $sueldoVariablePromedio = $registro ? floatval($registro['promedio']) : 0;
                $sueldoActivo = Sueldo_base::where('empleado_id', $empleado->id);
                $sueldoMensual = $sueldoActivo ? floatval($sueldoActivo->sueldo) : 0;
                $sueldoMensualPromedio = $sueldoVariablePromedio + $sueldoMensual;

                $feriados = Feriado::all();
                $arrayFeriados = array_column($feriados, 'fecha');

                $calculoDias = calcularPeriodoVacaciones($_POST['fecha_inicio'], $_POST['dias_solicitados'], $arrayFeriados);
                
                $vacacion->sincronizar($calculoDias);
                $vacacion->cargoEmpleado_id = $empleado->cargo_id;
                $vacacion->empresa_id = $empresaId;
                            
                $diasDeVacacion = calcularDiasVacacionesPorAntiguedad($empleado->ingreso);
                $vacacionesAnteriores = Vacaciones::where('empleado_id', $empleado->id);
                $saldoAnterior = !empty($vacacionesAnteriores) ? intval($vacacionesAnteriores->dias_restantes) : 0;
                $diasDisponiblesTotales = $diasDeVacacion['dias_disponibles'] + $saldoAnterior;
                $vacacion->dias_disponibles = $diasDisponiblesTotales;
                $vacacion->dias_adicionales = $diasDeVacacion['anos_servicio'];

                if ($vacacion->dias_solicitados > $vacacion->dias_disponibles) {
                    // Los días que rebasan el límite se van a anticipados
                    $vacacion->deduccion_diasAnticipados = $vacacion->dias_solicitados - $vacacion->dias_disponibles;
                    // Ya no quedan días disponibles en este periodo (todo se consumió y se pidió prestado)
                    $vacacion->dias_restantes = 0; 
                } else {
                    // Si pidió lo justo o menos, no hay días anticipados
                    $vacacion->deduccion_diasAnticipados = 0;
                    // Calculamos el saldo normal que le sobra
                    $vacacion->dias_restantes = $vacacion->dias_disponibles - $vacacion->dias_solicitados;
                }

                $salarioMinimo = Sueldo_minimo::get(1);
                $salarioMinimoActivo = array_shift($salarioMinimo);
                // Faov
                $faov = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'FAOV' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
                $faovActivo = !empty($faov) ? array_shift($faov) : null;
                // Ivss
                $ivss = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'IVSS' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
                $ivssActivo = !empty($ivss) ? array_shift($ivss) : null;
                // SPF
                $spf = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'SPF' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
                $spfActivo = !empty($spf) ? array_shift($spf) : null;
                
                $calculo = calcularVacaciones($sueldoMensualPromedio, $diasDeVacacion['anos_servicio'], $vacacion->dias_solicitados, $vacacion->deduccion_diasAnticipados, $salarioMinimoActivo->sueldo, $faovActivo->porcentaje, $spfActivo->porcentaje, $ivssActivo->porcentaje, $calculoDias);
                $vacacion->sincronizar($calculo);
                
                $vacacion->sueldo_mensual = $sueldoMensual;
                $vacacion->sueldo_diario = $sueldoMensual / 30;
                $vacacion->sueldo_variable_mensual = $sueldoVariablePromedio;
                $vacacion->sueldo_variable_diario = $sueldoVariablePromedio / 30;
                $vacacion->sueldo_diario_promedio = $calculo['sueldo_diario'];
                $vacacion->sueldo_mensual_promedio = $calculo['sueldo_mensual'];

                $vacacion->departamento_id = 1;

                $resultado = $vacacion->guardar();
                if($resultado){
                    header("Location: /admin/vacaciones?id={$empresaId}");
                }
            }
                
            }

            $router->render('admin/vacaciones/crear', [
                'titulo' => 'Crear Vacacion',
                'alertas' => $alertas,
                'empleados' => $empleados
            ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $vacacion = Vacaciones::find($id);

            if(!empty($vacacion)){
                $vacacion->estatus_id = 2;
                $resultado = $vacacion->guardar();
                if($resultado){
                    echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'La Vacacion se ha eliminado correctamente.'
                ]);
                return;
                }
            } else {
                // Si hay errores de validación
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'No se pudo actualizar eliminar la Vacacion.'
                ]);
                return;
            }
        }        
    }
}