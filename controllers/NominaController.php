<?php

namespace Controllers;

use Classes\Paginacion;
use Model\Empleado;
use Model\Empresa;
use Model\FaltasEmpleados;
use Model\Feriado;
use Model\Nomina;
use Model\NominaEmpleado;
use Model\NominaPeriodo;
use Model\ParametrosLegales;
use Model\Sueldo_base;
use Model\Sueldo_minimo;
use Model\Sueldo_variable;
use Model\TipoNomina;
use Model\Vacaciones;
use MVC\Router;

class NominaController{

    public static function empresa(Router $router){
        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        $registro_por_pagina = 10;

        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);       
        if(!$id){
           header('Location: /admin/nomina'); 
        }

        if(!isset($_GET['page'])) {
            header("Location: /admin/nomina/empresa?id={$id}&page=1");
            return;
        }

        $empresa = Empresa::find($id);
        $empleados = Empleado::total('empresa_id', $empresa->id);
        $nominas = Nomina::whereArray(['empresa_id' => $empresa->id]);


        if(!$pagina_actual || $pagina_actual < 1){
            header("Location: /admin/nomina/empresa?id={$id}&page=1");
        }

        $db = Nomina::getDB();
        $query = $db->query("SELECT COUNT(*) as total 
          FROM nominas
          WHERE empresa_id = {$empresa->id} AND estatus_id = '1'");
        $resultadoActivos = $query->fetch_assoc();
        $total = (int) ($resultadoActivos['total'] ?? 0);

        $paginacion = new Paginacion($pagina_actual, $registro_por_pagina, $total);

        if($paginacion->total_paginas() > 0 && $paginacion->total_paginas() < $pagina_actual){
            header("Location: /admin/nomina/empresa?id={$id}&page=1");
            return;
        }

        $limit = $registro_por_pagina;
        $offset = $paginacion->offset();
        $nominas = Nomina::consultarSQL("SELECT * FROM nominas WHERE empresa_id = '{$empresa->id}' AND estatus_id = '1' ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}");
        
        $router->render('admin/nominas/empresa', [
            'titulo' => 'Nomina - ' . $empresa->nombre,
            'empresa' => $empresa,
            'nominas' => $nominas,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router){
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $empresa = '';
        $alertas = [];
        $arrayFeriados = [];
        $tipoNomina = TipoNomina::all('ASC');
        $nominaPeriodo = NominaPeriodo::all();
        $nomina = new Nomina;
        $nominaEmpleado = new NominaEmpleado;

        if($id){
            $empresa = Empresa::find($id);
        }
        if(empty($empresa)){
            header('Location: /admin/nomina');
            return;
        }        
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            $feriados = Feriado::all();
            $arrayFeriados = array_column($feriados, 'fecha');
            $nomina->sincronizar($_POST);
            $nomina->empresa_id = $empresa->id;
            $salarioMinimo = Sueldo_minimo::get(1);
            $salarioMinimoActivo = array_shift($salarioMinimo);
            $nomina->salario_minimo = $salarioMinimoActivo->id;

            $faov = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'FAOV' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
            $faovActivo = !empty($faov) ? array_shift($faov) : null;
            
            $ivss = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'IVSS' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
            $ivssActivo = !empty($ivss) ? array_shift($ivss) : null;
            
            $spf = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'SPF' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
            $spfActivo = !empty($spf) ? array_shift($spf) : null;

            // Validamos que existan para evitar errores fatales
            if (!$faovActivo || !$ivssActivo || !$spfActivo) {
                throw new \Exception("Faltan parámetros legales configurados (FAOV, IVSS o SPF) en la base de datos.");
            }

            $nomina->porcentaje_faov = $faovActivo->id;
            $nomina->porcentaje_ivss = $ivssActivo->id;
            $nomina->porcentaje_spf = $spfActivo->id;

            $alertas = $nomina->validar();
            if(empty($alertas)){
                $db = Nomina::getDB();
                $db->begin_transaction();
                try{
                    $resultadoNomina = $nomina->guardar();
                    if(!$resultadoNomina['resultado']){
                        throw new \Exception("Error al guardar la nomina.");
                    }
                    
                    $nominaId = $resultadoNomina['id'];

                    // 1. Traemos tanto a los empleados regulares como a los que están de vacaciones
                    $empleadosRegulares = Empleado::whereArray(['estatus_id' => 1, 'empresa_id' => $empresa->id]);
                    $empleadosEnVacaciones = Empleado::whereArray(['estatus_id' => 3, 'empresa_id' => $empresa->id]);

                    // Unimos ambos grupos en una sola lista para procesarlos con la misma lógica
                    $todosLosEmpleados = array_merge($empleadosRegulares, $empleadosEnVacaciones);
                    $totalAsignacionNomina =  0;
                    $totalDeduccionNomina  = 0;
                    $totalEmpleados = 0;
                    $resumenSueldos = 0;
                    $resumenSabados = 0;
                    $resumenDomingos = 0;
                    $resumenFeriados = 0;
                    $resumenFaov = 0;
                    $resumenIvss = 0;
                    $resumenSpf = 0;
                    $resumenFaltas = 0;
                    if (!empty($todosLosEmpleados)) {
                        foreach ($todosLosEmpleados as $trabajador) {
                            
                            $arrayVacaciones = [];
                            $faltasEmpleados = FaltasEmpleados::whereArray(['empleado_id' => $trabajador->id]);
                            $arrayFalta = !empty($faltasEmpleados) ? array_column($faltasEmpleados, 'fecha') : [];
                            // Si el empleado está de vacaciones (estatus_id = 3), buscamos sus días
                            $fechaInicioVacacion = null;
                            $fechaFinVacacion = null;
                            $estatusEmpleado = null;

                            // Buscamos si existe alguna vacación del empleado que se cruce con las fechas de esta nómina
                            $vacacionesEmpleado = Vacaciones::consultarSQL("
                                SELECT * FROM vacaciones 
                                WHERE empleado_id = {$trabajador->id} 
                                AND fecha_inicio <= '{$nomina->fecha_fin}' 
                                AND fecha_fin >= '{$nomina->fecha_inicio}'
                                LIMIT 1
                            ");

                            $vacacionAfectada = !empty($vacacionesEmpleado) ? array_shift($vacacionesEmpleado) : null;

                            if ($vacacionAfectada) {
                                // Capturamos la fecha de inicio y fin de la vacación que ocurrió en este periodo de nómina
                                $fechaInicioVacacion = $vacacionAfectada->fecha_inicio;
                                $fechaFinVacacion = $vacacionAfectada->fecha_fin;

                                $inicioVacacion = new \DateTime($fechaInicioVacacion);
                                $finVacacion = new \DateTime($fechaFinVacacion); 
                                $finVacacion->modify('+1 day');

                                $periodoVacacion = new \DatePeriod($inicioVacacion, new \DateInterval('P1D'), $finVacacion);

                                foreach ($periodoVacacion as $dia) {
                                    $arrayVacaciones[] = $dia->format('Y-m-d');
                                }
                                $estatusEmpleado = 3;
                            }

                            $resultadoSueldo = Sueldo_base::consultarSQL("SELECT * FROM sueldo_base WHERE empleado_id = '{$trabajador->id}' ORDER BY fecha DESC LIMIT 1");
                            $sueldoActivo = !empty($resultadoSueldo) ? array_shift($resultadoSueldo) : null;

                            $estadistica = calcularNominas($nomina->fecha_inicio, $nomina->fecha_fin, $arrayFeriados, $arrayVacaciones, $arrayFalta);
                            // 4. Preparamos y guardamos el registro en la tabla intermedia (INSTANCIA NUEVA EN CADA VUELTA)
                            $nominaEmpleado = new NominaEmpleado;
                            $nominaEmpleado->sincronizar($estadistica);
                            $nominaEmpleado->empleado_id = $trabajador->id;
                            $nominaEmpleado->cargo_id = $trabajador->cargo_id;
                            $nominaEmpleado->sueldo_base = $sueldoActivo ? $sueldoActivo->sueldo : 0;
                            $nominaEmpleado->vacacion_id = $vacacionAfectada ? $vacacionAfectada->id : null;
                            $nominaEmpleado->nomina_id = $nominaId;   
                            $nominaEmpleado->estatus_empleado = $estatusEmpleado ?? $trabajador->estatus_id;
                            $nominaEmpleado->inicio_vacacion = $fechaInicioVacacion;
                            $nominaEmpleado->fin_vacacion = $fechaFinVacacion;

                            $calculoNomina = calcularAsignacionDeduccion($nominaEmpleado->sueldo_base, $salarioMinimoActivo->sueldo, $ivssActivo->porcentaje, $faovActivo->porcentaje, $spfActivo->porcentaje, $estadistica);
                            $nominaEmpleado->sincronizar($calculoNomina);

                            $resultadoNominaEmpleado = $nominaEmpleado->guardar();

                            if (!$resultadoNominaEmpleado['resultado']) {
                                throw new \Exception("Error al guardar la nomina del empleado ID: {$trabajador->id}");
                            }
                            $resumenSueldos += floatval($calculoNomina['asignacion_sueldo'] ?? 0);
                            $resumenSabados += floatval($calculoNomina['asignacion_sabado'] ?? 0);
                            $resumenDomingos += floatval($calculoNomina['asignacion_domingo'] ?? 0);
                            $resumenFeriados += floatval($calculoNomina['asignacion_feriado'] ?? 0);
                            $resumenFaov += floatval($calculoNomina['deduccion_faov'] ?? 0);
                            $resumenIvss += floatval($calculoNomina['deduccion_ivss'] ?? 0);
                            $resumenSpf += floatval($calculoNomina['deduccion_spf'] ?? 0);
                            $resumenFaltas += floatval($calculoNomina['deduccion_faltas'] ?? 0);
                            $totalAsignacionNomina += floatval($calculoNomina['asignacion_total'] ?? 0);
                            $totalDeduccionNomina  += floatval($calculoNomina['deduccion_total'] ?? 0);
                            $totalEmpleados++;
                        }
                    }
                    
                    $nomina->resumenTotal_asignacion = $totalAsignacionNomina;
                    $nomina->resumenTotal_deduccion  = $totalDeduccionNomina;
                    $nomina->resumen_total = $totalAsignacionNomina - $totalDeduccionNomina;
                    $nomina->total_empleados = $totalEmpleados;

                    $nomina->resumen_sueldos = $resumenSueldos;
                    $nomina->resumen_sabados = $resumenSabados;
                    $nomina->resumen_domingos = $resumenDomingos;
                    $nomina->resumen_feriados = $resumenFeriados;

                    // Deducciones / Retenciones detalladas
                    $nomina->resumen_faov = $resumenFaov;
                    $nomina->resumen_ivss = $resumenIvss;
                    $nomina->resumen_spf = $resumenSpf;
                    $nomina->resumen_faltas = $resumenFaltas;

                    // Volvemos a guardar para actualizar los totales en la cabecera
                    $resultadoActualizarNomina = $nomina->actualizar();
                    if (!$resultadoActualizarNomina) {
                        throw new \Exception("Error al actualizar los totales de la nómina.");
                    }
                    $db->commit();
                    header('Location: /admin/nomina/empresa?id=' . $empresa->id);
                    return;

                }catch (\Exception $e) {
                    // Si algo falló, echamos marcha atrás a todo
                    $db->rollback();
                    $alertas['error'][] = $e->getMessage();
                }
            }


        }

        
        $router->render('admin/nominas/crear', [
            'titulo' => 'Nomina - ' . $empresa->nombre,
            'empresa' => $empresa,
            'tipoNomina' => $tipoNomina,
            'nominaPeriodo' => $nominaPeriodo,
            'alertas' => $alertas
        ]);
    }

    public static function crearVariable(Router $router){
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $alertas = [];
        $tipoNomina = TipoNomina::all('ASC');
        $nominaPeriodo = NominaPeriodo::all();
        $nomina = new Nomina;
        $nominaEmpleado = new NominaEmpleado;
        $arrayFeriados = [];
        
        $empresa = Empresa::find($id);
    
        $empleados = Empleado::whereArray(['empresa_id' => $empresa->id, 'estatus_id' => 1]);
        $empleados_vacaciones = Empleado::whereArray(['empresa_id' => $empresa->id, 'estatus_id' => 3]);
        $todosLosEmpleados = array_merge($empleados, $empleados_vacaciones);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            $nomina->sincronizar($_POST);
    
            $feriados = Feriado::all();
            $arrayFeriados = array_column($feriados, 'fecha');
            
            $nomina->empresa_id = $empresa->id;
            
            $salarioMinimo = Sueldo_minimo::get(1);
            $salarioMinimoActivo = array_shift($salarioMinimo);
            $nomina->salario_minimo = $salarioMinimoActivo->id;

            $faov = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'FAOV' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
            $faovActivo = !empty($faov) ? array_shift($faov) : null;
            
            $ivss = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'IVSS' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
            $ivssActivo = !empty($ivss) ? array_shift($ivss) : null;
            
            $spf = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'SPF' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
            $spfActivo = !empty($spf) ? array_shift($spf) : null;

            // Validamos que existan para evitar errores fatales
            if (!$faovActivo || !$ivssActivo || !$spfActivo) {
                throw new \Exception("Faltan parámetros legales configurados (FAOV, IVSS o SPF) en la base de datos.");
            }

            $nomina->porcentaje_faov = $faovActivo->id;
            $nomina->porcentaje_ivss = $ivssActivo->id;
            $nomina->porcentaje_spf = $spfActivo->id;
            
            $alertas = $nomina->validar();
            
            if(empty($alertas)){
                $db = Nomina::getDB();
                $db->begin_transaction();
                try{
                    $resultadoNomina = $nomina->guardar();
                    if(!$resultadoNomina['resultado']){
                        throw new \Exception("Error al guardar la nomina.");
                    }
                    
                    $nominaId = $resultadoNomina['id'];

                    // 1. Recibimos los IDs de los empleados seleccionados y los sueldos escritos
                    $empleadosSeleccionados = $_POST['empleados_ids'] ?? [];
                    $sueldosVariables = $_POST['sueldo_variable'] ?? [];

                    $totalAsignacionNomina = 0;
                    $totalDeduccionNomina = 0;
                    $totalEmpleados = 0;
                    // (Tus demás variables acumuladoras de resumen...)

                    if (!empty($empleadosSeleccionados)) {
                        foreach ($empleadosSeleccionados as $empleadoId) {
                            $trabajador = Empleado::find($empleadoId);
                            if(!$trabajador) continue;

                            // 2. Obtenemos el sueldo variable asignado en la fila de ese empleado
                            // Si dejaron el campo vacío, por seguridad toma 0 o puedes asignarle su sueldo base fijo si prefieres.
                            $sueldoPersonalizado = str_replace(',', '.', $sueldosVariables[$empleadoId])  ?? 0;
                            $sueldoPersonalizado = floatval($sueldoPersonalizado);

                            // 3. Manejo de faltas y vacaciones (tu lógica existente)
                            $arrayVacaciones = [];
                            $faltas = FaltasEmpleados::whereArray(['empleado_id' => $trabajador->id]);
                            $arrayFalta = !empty($faltas) ? array_column($faltas, 'fecha') : [];

                            $fechaInicioVacacion = null;
                            $fechaFinVacacion = null;
                            $estatusEmpleado = null;

                            // Buscamos si existe alguna vacación del empleado que se cruce con las fechas de esta nómina
                            $vacacionesEmpleado = Vacaciones::consultarSQL("
                                SELECT * FROM vacaciones 
                                WHERE empleado_id = {$trabajador->id} 
                                AND fecha_inicio <= '{$nomina->fecha_fin}' 
                                AND fecha_fin >= '{$nomina->fecha_inicio}'
                                LIMIT 1
                            ");

                            $vacacionAfectada = !empty($vacacionesEmpleado) ? array_shift($vacacionesEmpleado) : null;

                            if ($vacacionAfectada) {
                                // Capturamos la fecha de inicio y fin de la vacación que ocurrió en este periodo de nómina
                                $fechaInicioVacacion = $vacacionAfectada->fecha_inicio;
                                $fechaFinVacacion = $vacacionAfectada->fecha_fin;

                                $inicioVacacion = new \DateTime($fechaInicioVacacion);
                                $finVacacion = new \DateTime($fechaFinVacacion); 
                                $finVacacion->modify('+1 day');

                                $periodoVacacion = new \DatePeriod($inicioVacacion, new \DateInterval('P1D'), $finVacacion);

                                foreach ($periodoVacacion as $dia) {
                                    $arrayVacaciones[] = $dia->format('Y-m-d');
                                }
                                $estatusEmpleado = 3;
                            }


                            // 4. Calculamos estadísticas de días (días hábiles, feriados, etc.)
                            $estadistica = calcularNominas($nomina->fecha_inicio, $nomina->fecha_fin, $arrayFeriados, $arrayVacaciones, $arrayFalta);

                            // 5. Creamos el registro del empleado en la nómina usando el SUELDO VARIABLE
                            $nominaEmpleado = new NominaEmpleado;
                            $nominaEmpleado->sincronizar($estadistica);
                            $nominaEmpleado->empleado_id = $trabajador->id;
                            $nominaEmpleado->cargo_id = $trabajador->cargo_id;
                            $nominaEmpleado->sueldo_base = $sueldoPersonalizado; // <--- AQUí SE USA EL SUELDO VARIABLE
                            $nominaEmpleado->vacacion_id = $vacacionAfectada ? $vacacionAfectada->id : null;
                            $nominaEmpleado->nomina_id = $nominaId;
                            $nominaEmpleado->estatus_empleado = $estatusEmpleado ?? $trabajador->estatus_id;
                            $nominaEmpleado->inicio_vacacion = $fechaInicioVacacion;
                            $nominaEmpleado->fin_vacacion = $fechaFinVacacion;

                            // Realizamos el cálculo matemático con el sueldo variable de este trabajador
                            $calculoNomina = calcularAsignacionDeduccion($nominaEmpleado->sueldo_base, $salarioMinimoActivo->sueldo, $ivssActivo->porcentaje, $faovActivo->porcentaje, $spfActivo->porcentaje, $estadistica);
                            
                            $nominaEmpleado->sincronizar($calculoNomina);
                            $resultadoNominaEmpleado = $nominaEmpleado->guardar();

                            if (!$resultadoNominaEmpleado['resultado']) {
                                throw new \Exception("Error al guardar la nomina del empleado ID: {$trabajador->id}");
                            }

                            // Acumuladores generales...
                            $totalAsignacionNomina += floatval($calculoNomina['asignacion_total'] ?? 0);
                            $totalDeduccionNomina  += floatval($calculoNomina['deduccion_total'] ?? 0);
                            $totalEmpleados++;

                            $sueldo_variable_guardar = new Sueldo_variable;
                            $sueldo_variable_guardar->sueldo = $sueldoPersonalizado;
                            $sueldo_variable_guardar->empleado_id = $trabajador->id;
                            $sueldo_variable_guardar->fecha = $nomina->fecha_fin;
                            $resultadoSueldoVariable = $sueldo_variable_guardar->guardar();
                            if (!$resultadoSueldoVariable['resultado']) {
                                throw new \Exception("Error al guardar el sueldo variable del empleado: {$trabajador->nombres}");
                            }
                        }
                    }

                    // Actualizamos los totales de la cabecera de la nómina
                    $nomina->resumenTotal_asignacion = $totalAsignacionNomina;
                    $nomina->resumenTotal_deduccion  = $totalDeduccionNomina;
                    $nomina->resumen_total = $totalAsignacionNomina - $totalDeduccionNomina;
                    $nomina->total_empleados = $totalEmpleados;

                    $resultadoActualizarNomina = $nomina->actualizar();
                    if (!$resultadoActualizarNomina) {
                        throw new \Exception("Error al actualizar los totales de la nómina.");
                    }

                    $db->commit();
                    header('Location: /admin/nomina/empresa?id=' . $empresa->id);
                    return;

                }catch (\Exception $e) {
                    $db->rollback();
                    $alertas['error'][] = $e->getMessage();
                }
            }
        }

        $router->render('admin/nominas/nominaVariable', [
            'titulo' => 'Nomina - ' . $empresa->nombre,
            'alertas' => $alertas,
            'tipoNomina' => $tipoNomina,
            'nominaPeriodo' => $nominaPeriodo,
            'empleados' => $todosLosEmpleados
        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $nomina = Nomina::find($id);

            if(!empty($nomina)){
                $nomina->estatus_id = 2;
                $resultado = $nomina->guardar();
                if($resultado){
                    echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'La nomina se ha eliminado correctamente.'
                ]);
                return;
                }
            } else {
                // Si hay errores de validación
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'No se pudo eliminar la Nomina.'
                ]);
                return;
            }
        }        
    }
}