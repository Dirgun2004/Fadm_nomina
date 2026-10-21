<?php

namespace Controllers;

use Classes\Paginacion;
use Model\Contribuyente;
use Model\Empleado;
use Model\Empresa;
use Model\Estatus;
use Model\Nomina;
use MVC\Router;

class EmpresasController{
    public static function index(Router $router){

        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual || $pagina_actual < 1){
            header('Location: /admin/empresas?page=1');
        }      

        $registro_por_pagina = 10;
        $total = Empresa::total('estatus_id', '1');

        $paginacion = new Paginacion($pagina_actual, $registro_por_pagina, $total);

        if($paginacion->total_paginas() < $pagina_actual){
            header('Location: /admin/empresas?page=1');
        }

        $offset = $paginacion->offset();
        $query = "SELECT * FROM empresas WHERE estatus_id = '1' ORDER BY id DESC LIMIT {$registro_por_pagina} OFFSET {$offset}";
        $empresas = Empresa::consultarSQL($query);

        foreach($empresas as $empresa){
            $empresa->estatus = Estatus::find($empresa->estatus_id);
            $empresa->contribuyente = Contribuyente::find($empresa->contribuyente_id);
        }        
    
        
        $router->render('admin/empresas/empresas', [
            'titulo' => 'Empresas',
            'empresas' => $empresas,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router){
        $alertas = [];
        $empresa = new Empresa;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            $empresa->sincronizar($_POST);
            $alertas = $empresa->validar();

            if(empty($alertas)){
                $resultado = $empresa->guardar();
                if($resultado){
                    header('Location: /admin/empresas');
                }
            }

        }


        $router->render('admin/empresas/crear', [
            'titulo' => 'Crear Empresa',
            'empresa' => $empresa,
            'alertas' => $alertas
        ]);
    }

    public static function editar(Router $router){
        $alertas = [];
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $alertas = [];

        if(!$id){
            header('Location: /admin/empresas');
        }

        $empresa = Empresa::find($id);

        if(empty($empresa)){
            header('Location: /admin/empresas');
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            $empresa->sincronizar($_POST);
            $alertas = $empresa->validar();

            if(empty($alertas)){
                $resultado = $empresa->guardar();
                if($resultado){
                    header('Location: /admin/empresas');
                }
            }

        }


        $router->render('admin/empresas/editar', [
            'titulo' => 'Editar Empresa',
            'empresa' => $empresa,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $empresa = Empresa::find($id);

            if(!empty($empresa)){
                $empresa->estatus_id = 2;
                $resultado = $empresa->guardar();
                if($resultado){
                    echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'El estatus de la empresa ha sido actualizada correctamente.'
                ]);
                return;
                }
            } else {
                // Si hay errores de validación
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'No se pudo actualizar el estatus de la empresa.'
                ]);
                return;
            }
        }
    }

    public static function detalle(Router $router){
        $idEmpresa = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if(!$idEmpresa) {
            header('Location: /admin/nomina'); 
            return;
        }

        $empresa = Empresa::find($idEmpresa);
        if(!$empresa) {
            header('Location: /admin/nomina');
            return;
        }

        // Obtenemos la conexión a la base de datos de tu framework
        $db = Empresa::getDB();

        // 1. Empleados de vacaciones (estatus_id = '3')
        $queryVacaciones = $db->query("SELECT COUNT(*) as total FROM empleados WHERE empresa_id = {$empresa->id} AND estatus_id = '3'");
        $resultadoVacaciones = $queryVacaciones->fetch_assoc();
        $empleadosVacaciones = (int) ($resultadoVacaciones['total'] ?? 0);

        // 2. Empleados activos (estatus_id = '1')
        $queryActivos = $db->query("SELECT COUNT(*) as total FROM empleados WHERE empresa_id = {$empresa->id} AND estatus_id = '1'");
        $resultadoActivos = $queryActivos->fetch_assoc();
        $empleadosActivos = (int) ($resultadoActivos['total'] ?? 0);
        $empleadosActivos += $empleadosVacaciones;
        
        // 3. Empleados inactivos (estatus_id = '2')
        $queryInactivos = $db->query("SELECT COUNT(*) as total FROM empleados WHERE empresa_id = {$empresa->id} AND estatus_id = '2'");
        $resultadoInactivos = $queryInactivos->fetch_assoc();
        $empleadosInactivos = (int) ($resultadoInactivos['total'] ?? 0);

        $queryActivos = $db->query("SELECT COUNT(*) as total FROM nominas WHERE empresa_id = {$empresa->id} AND estatus_id = '1'");
        $resultadoActivos = $queryActivos->fetch_assoc();
        $nominasActivos = (int) ($resultadoActivos['total'] ?? 0);
        
        $queryActivos = $db->query("SELECT COUNT(*) as total FROM cestaticket WHERE empresa_id = {$empresa->id} AND estatus_id = '1'");
        $resultadoActivos = $queryActivos->fetch_assoc();
        $cestaticketActivos = (int) ($resultadoActivos['total'] ?? 0);
        
        $queryActivos = $db->query("SELECT COUNT(*) as total FROM vacaciones WHERE empresa_id = {$empresa->id} AND estatus_id = '1'");
        $resultadoActivos = $queryActivos->fetch_assoc();
        $vacacionesActivos = (int) ($resultadoActivos['total'] ?? 0);

        $queryActivos = $db->query("SELECT COUNT(*) as total 
          FROM bono_empleado
          WHERE empresa_id = {$empresa->id} AND estatus_id = '1'");
        $resultadoActivos = $queryActivos->fetch_assoc();
        $bonosActivos = (int) ($resultadoActivos['total'] ?? 0);
     
        $router->render('admin/empresas/detalle', [
            'titulo' => $empresa->nombre,
            'empresa' => $empresa,
            'empleadosInactivos' => $empleadosInactivos,
            'empleadosActivos' => $empleadosActivos,
            'empleadosVacaciones' => $empleadosVacaciones,
            'nominas' => $nominasActivos,
            'cestaticket' => $cestaticketActivos,
            'vacaciones' => $vacacionesActivos,
            'bonos' => $bonosActivos
        ]);
    }
}