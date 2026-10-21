<?php

namespace Controllers;

use Classes\Paginacion;
use Model\Cestaticket;
use Model\CestaticketEmpleado;
use Model\Empleado;
use Model\Empresa;
use Model\FaltasEmpleados;
use Model\Nomina;
use MVC\Router;

class CestaticketController{

    public static function empresa(Router $router){
        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        $registro_por_pagina = 10;

        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);       
        if(!$id){
           header('Location: /admin/cestaticket'); 
           return;
        }

        if(!isset($_GET['page'])) {
            header("Location: /admin/cestaticket/empresa?id={$id}&page=1");
            return;
        }

        $empresa = Empresa::find($id);
        $empleados = Empleado::total('empresa_id', $empresa->id);

        if(!$pagina_actual || $pagina_actual < 1){
            header("Location: /admin/cestaticket/empresa?id={$id}&page=1");
        }

        $db = Cestaticket::getDB();

        $query = $db->query("SELECT COUNT(*) as total 
          FROM cestaticket
          WHERE empresa_id = {$empresa->id} AND estatus_id = '1'");
        $resultadoActivos = $query->fetch_assoc();
        $total = (int) ($resultadoActivos['total'] ?? 0);

        $paginacion = new Paginacion($pagina_actual, $registro_por_pagina, $total);

        if($paginacion->total_paginas() > 0 && $paginacion->total_paginas() < $pagina_actual){
            header("Location: /admin/cestaticket/empresa?id={$id}&page=1");
            return;
        }

        $limit = $registro_por_pagina;
        $offset = $paginacion->offset();
        $cestatickets = Cestaticket::consultarSQL("SELECT * FROM cestaticket WHERE empresa_id = '{$empresa->id}' AND estatus_id = '1' LIMIT {$limit} OFFSET {$offset}");
        
        $router->render('admin/cestaticket/empresa', [
            'titulo' => 'CestaTicket - ' . $empresa->nombre,
            'empresa' => $empresa,
            'cestatickets' => $cestatickets,
            'totalEmpleados' => $empleados,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router){
        $alertas = [];
        $cestaticket = new Cestaticket;

        $id_empresa = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if(!$id_empresa){
            header('Location: /admin/cestaticket');
            return;
        }

        $empresa = Empresa::find($id_empresa);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            if(isset($_POST['monto']) && isset($_POST['tasa'])){
                $_POST['monto'] = str_replace(',', '.', $_POST['monto']);
                $_POST['tasa'] = str_replace(',', '.', $_POST['tasa']);
            }
            $cestaticket->sincronizar($_POST);
            $cestaticket->empresa_id = $empresa->id;
            $alertas = $cestaticket->validar();
            // debuguear($cestaticket);
            if(empty($alertas)){
                $db = Cestaticket::getDB();
                $db->begin_transaction();
                try {
                    $resultadoTicket = $cestaticket->guardar();
                    if(!$resultadoTicket['resultado']){
                        throw new \Exception("Error al generar el cestaticket.");
                    }
                    
                    $ticketId = $resultadoTicket['id'];

                    $empleadosRegulares = Empleado::whereArray(['estatus_id' => 1, 'empresa_id' => $empresa->id]);
                    $empleadosEnVacaciones = Empleado::whereArray(['estatus_id' => 3, 'empresa_id' => $empresa->id]);

                    // Unimos ambos grupos en una sola lista para procesarlos con la misma lógica
                    $todosLosEmpleados = array_merge($empleadosRegulares, $empleadosEnVacaciones);
                    $totalAsignacion = 0;
                    $totalDeduccion = 0;
                    $totalFaltas = 0;
                    $totalTrabajadores = 0;
                    if(!empty($todosLosEmpleados)){
                        foreach($todosLosEmpleados as $empleado){
                            $cestaticketEmpleado = new CestaticketEmpleado;
                            $cestaticketEmpleado->cestaticket_id = $ticketId;
                            $cestaticketEmpleado->empleado_id = $empleado->id;
                            $cestaticketEmpleado->cargo_empleado = $empleado->cargo_id;

                            $arrayFaltasObj = FaltasEmpleados::whereArray(['empleado_id' => $empleado->id]);
                            $arrayFaltas = array_column($arrayFaltasObj, 'fecha');
                            $calculoTicket = calcularTicket($cestaticket->monto, $cestaticket->tasa, $cestaticket->periodo_inicio, $cestaticket->periodo_fin, $arrayFaltas);

                            $cestaticketEmpleado->asignacion = $calculoTicket['asignacion'];
                            $cestaticketEmpleado->deduccion = $calculoTicket['deduccion'];
                            $cestaticketEmpleado->faltas = $calculoTicket['faltas'];
                            $cestaticketEmpleado->total_calculo = $calculoTicket['total_calculo'];
                            $cestaticketEmpleado->dias_trabajados = $calculoTicket['dias_trabajados'];

                            $resultadoTicketEmpleado = $cestaticketEmpleado->guardar();
                            if(!$resultadoTicketEmpleado['resultado']){
                                throw new \Exception("Error al generar el cestaticket.");
                            }
                            $totalAsignacion += floatval($calculoTicket['asignacion'] ?? 0);
                            $totalDeduccion += floatval($calculoTicket['deduccion'] ?? 0);
                            $totalFaltas += $calculoTicket['faltas'] ?? 0;
                            $totalDias = $calculoTicket['periodo_dias'] ?? 0;
                            $montoDiario = $calculoTicket['monto_diario'] ?? 0;
                            $totalTrabajadores++;
                        }
                    }
                    
                    $cestaticket->total_asignacion = $totalAsignacion;
                    $cestaticket->total_deduccion = $totalDeduccion;
                    $cestaticket->total_trabajadores = $totalTrabajadores;
                    $cestaticket->monto_diario = $montoDiario;
                    $cestaticket->periodo_dias = $totalDias;
                    $cestaticket->total_faltas = $totalFaltas;
                    $cestaticket->total_calculo = $totalAsignacion - $totalDeduccion;
                    $resultadoTicketAct = $cestaticket->actualizar();
                    if(!$resultadoTicketAct){
                        throw new \Exception("Error al generar el cestaticket.");
                    }
                    $db->commit();
                    header('Location: /admin/cestaticket/empresa?id=' . $empresa->id);
                    return;

                } catch (\Throwable $e) {
                    $db->rollback();
                    $alertas['error'][] = $e->getMessage();
                }
            }

        }

        $router->render('admin/cestaticket/crear', [
            'titulo' => 'CestaTicket',
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $ticket = Cestaticket::find($id);

            if(!empty($ticket)){
                $ticket->estatus_id = 2;
                $resultado = $ticket->guardar();
                if($resultado){
                    echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'El CestaTicket se ha eliminado correctamente.'
                ]);
                return;
                }
            } else {
                // Si hay errores de validación
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'No se pudo eliminar el CestaTicket.'
                ]);
                return;
            }
        }        
    }

}