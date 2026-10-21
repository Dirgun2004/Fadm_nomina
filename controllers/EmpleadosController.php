<?php

namespace Controllers;

use Classes\Paginacion;
use Model\Cargo;
use Model\Contribuyente;
use Model\Departamento;
use Model\Empleado;
use Model\Empresa;
use Model\Estatus;
use Model\Sueldo_base;
use Model\Sueldo_variable;
use Model\TipoDocumento;
use MVC\Router;

class EmpleadosController{
    public static function index(Router $router){
        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        $registro_por_pagina = 10;

        $id_empresa = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if(!$id_empresa){
            header('Location: /admin/empresas');
            return;
        }
            
        if(!$pagina_actual || $pagina_actual < 1){
            header("Location: /admin/empleados?id={$id_empresa}&page=1");
            return;
        }

        // 1. Contar el total de empleados de esta empresa que tengan estatus 1 o 3
        $db = Empleado::getDB();
        $queryTotal = "SELECT COUNT(*) FROM empleados WHERE empresa_id = {$id_empresa} AND estatus_id IN (1, 3)";
        $resultadoTotal = $db->query($queryTotal);
        $total = $resultadoTotal->fetch_array()[0];

        $paginacion = new Paginacion($pagina_actual, $registro_por_pagina, $total);

        if($paginacion->total_paginas() > 0 && $paginacion->total_paginas() < $pagina_actual){
            header("Location: /admin/empleados?id={$id_empresa}&page=1");
            return;
        }
        
        // 2. Traer los empleados paginados aplicando el filtro de empresa y estatus (1 y 3)
        $offset = $paginacion->offset();
        $query = "SELECT * FROM empleados WHERE empresa_id = {$id_empresa} AND estatus_id IN (1, 3) ORDER BY id DESC LIMIT {$registro_por_pagina} OFFSET {$offset}";
        $empleados = Empleado::consultarSQL($query);

        // 3. Buscar sus relaciones (documento, cargo, estatus)
        foreach($empleados as $empleado){
            $empleado->documento = TipoDocumento::find($empleado->documento_id);
            $empleado->cargo = Cargo::find($empleado->cargo_id);
            $empleado->estatus = Estatus::find($empleado->estatus_id);
        }
        
        $router->render('admin/empleado/index', [
            'titulo' => 'Empleados',
            'empleados' => $empleados,
            'paginacion' => $paginacion->paginacion()
        ]);
    }
    

    public static function crear(Router $router){
        $empleado = new Empleado;
        $sueldo_base = new Sueldo_base;        
        $alertas = [];
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        date_default_timezone_set('America/Caracas');
        $fechaActual = date('Y-m-d');

        if(!$id_empresa){
            header('Location: /admin/empresas');
            return;
        }

        $cargos = Cargo::all();
        $departamentos = Departamento::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $empresa = Empresa::find($id_empresa);
            
        $_POST['empleado'] = array_map('trim', $_POST['empleado']);
        $_POST['sueldo_base'] = array_map('trim', $_POST['sueldo_base']);

            if(empty($empresa)){
                header('Location: /admin/empresas');
                return;
            }
            if(isset($_POST['empleado']['cedula'])){
                $_POST['empleado']['cedula'] = str_replace('.', '', $_POST['empleado']['cedula']);
            }
            if(isset($_POST['empleado']['rif'])){
                $_POST['empleado']['rif'] = str_replace('.', '', $_POST['empleado']['rif']);
                $_POST['empleado']['rif'] = str_replace('-', '', $_POST['empleado']['rif']);
            }
            // -- EMPLEADO --
            $empleado->sincronizar($_POST['empleado']);
            $empleado->empresa_id = $empresa->id;
            $alertas = $empleado->validar();
            
            if(isset($_POST['sueldo_base']['sueldo'])) {
                $_POST['sueldo_base']['sueldo'] = str_replace(',', '.', $_POST['sueldo_base']['sueldo']);
            }

            $sueldo_base->sincronizar($_POST['sueldo_base']);
            $alertas = array_merge($alertas, $sueldo_base->validar());

            if(empty($alertas)){
                $db = Empleado::getDB();
                $db->begin_transaction();
                try{
                    $resultadoEmpleado = $empleado->guardar();
                    if(!$resultadoEmpleado['resultado']){
                        throw new \Exception("Error al guardar el empleado.");
                    }

                    $empleadoId = $resultadoEmpleado['id'];

                    // -- SUELDO BASE --
                    $sueldo_base->empleado_id = $empleadoId;
                    $resultadoBase = $sueldo_base->guardar();
                    if(!$resultadoBase) {
                        throw new \Exception("Error al guardar el sueldo base.");
                    }
                    
                    $db->commit();
                    header('Location: /admin/empleados?id=' . $id_empresa);
                    return;
                }catch (\Exception $e) {
                    // Si algo falló, echamos marcha atrás a todo
                    $db->rollback();
                    $alertas['error'][] = $e->getMessage();
                }
            }
        }
        
        $router->render('admin/empleado/crear', [
            'titulo' => 'Registrar Empleado',
            'empleado' => $empleado,
            'alertas' => $alertas,
            'cargos' => $cargos,
            'fechaActual' => $fechaActual,
            'departamentos' => $departamentos

        ]);
    }

    public static function editar(Router $router){
        $alertas = [];
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($id_empresa);
        $empleado = Empleado::find($id);

        if(empty($id) || empty($empleado)){
            header('Location: /admin/empleados?id=' . $id_empresa);
            return;
        }

        $cargos = Cargo::all();
        $departamentos = Departamento::all();
        
        // Recuperar el sueldo base existente para actualizarlo y no duplicarlo
        $sueldo_base = Sueldo_base::where('empleado_id', $empleado->id);
        if(!$sueldo_base) {
            $sueldo_base = new Sueldo_base;
        }

        $empleado->sueldo = $sueldo_base;
        $empleado->variable = Sueldo_variable::where('empleado_id', $empleado->id);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['empleado']) && is_array($_POST['empleado'])){
                $_POST['empleado'] = array_map('trim', $_POST['empleado']);
            }
            if(isset($_POST['sueldo_base']) && is_array($_POST['sueldo_base'])){
                $_POST['sueldo_base'] = array_map('trim', $_POST['sueldo_base']);
            }

            if(empty($empresa)){
                header('Location: /admin/empresas');
                return;
            }

            // Limpieza correcta dentro del array de empleado
            if(isset($_POST['empleado']['cedula'])){
                $_POST['empleado']['cedula'] = str_replace('.', '', $_POST['empleado']['cedula']);
            }
            if(isset($_POST['empleado']['rif'])){
                $_POST['empleado']['rif'] = str_replace('.', '', $_POST['empleado']['rif']);
                $_POST['empleado']['rif'] = str_replace('-', '', $_POST['empleado']['rif']);
            }

            // -- EMPLEADO --
            $empleado->sincronizar($_POST['empleado']);
            $empleado->empresa_id = $empresa->id;
            $alertas = $empleado->validar();
            
            if(isset($_POST['sueldo_base']['sueldo'])) {
                $_POST['sueldo_base']['sueldo'] = str_replace(',', '.', $_POST['sueldo_base']['sueldo']);
            }

            $sueldo_base->sincronizar($_POST['sueldo_base']);
            $alertas = array_merge($alertas, $sueldo_base->validar());

            if(empty($alertas)){
                $db = Empleado::getDB();
                $db->begin_transaction();
                try{
                    $resultadoEmpleado = $empleado->guardar();
                    if(!$resultadoEmpleado){
                        throw new \Exception("Error al actualizar el empleado.");
                    }

                    // -- SUELDO BASE --
                    $sueldo_base->empleado_id = $empleado->id;
                    $resultadoBase = $sueldo_base->guardar();
                    if(!$resultadoBase) {
                        throw new \Exception("Error al actualizar el sueldo base.");
                    }
                    
                    $db->commit();
                    header('Location: /admin/empleados?id=' . $id_empresa);
                    return;
                }catch (\Exception $e) {
                    $db->rollback();
                    $alertas['error'][] = $e->getMessage();
                }
            }
        }
        
        $router->render('admin/empleado/editar', [
            'titulo' => 'Editar Empleado',
            'alertas' => $alertas,
            'empleado' => $empleado,
            'cargos' => $cargos,
            'departamentos' => $departamentos
        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $empleado = Empleado::find($id);

            if(!empty($empleado)){
                $empleado->estatus_id = 2;
                $resultado = $empleado->guardar();
                if($resultado){
                    echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'El estatus del empleado ha sido actualizado correctamente.'
                ]);
                return;
                }
            } else {
                // Si hay errores de validación
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'No se pudo actualizar el estatus del empleado.'
                ]);
                return;
            }
        }
    }
}