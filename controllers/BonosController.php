<?php
namespace Controllers;

use Classes\Paginacion;
use Model\BonoEmpleado;
use Model\Bonos;
use Model\Empleado;
use Model\Empresa;
use Model\NominaPeriodo;
use MVC\Router;

class BonosController{

    public static function empresa(Router $router) {
        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        // Capturamos el ID de la empresa desde la URL
        $id_empresa = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if(!$id_empresa){
            header('Location: /admin/bonos');
            return;
        }

        if(!$pagina_actual || $pagina_actual < 1){
            header("Location: /admin/bonos/empresa?id={$id_empresa}&page=1");
            return;
        }

        $registro_por_pagina = 12; 
        
        // Obtenemos el total de meses únicos de ESTA empresa
        $total = BonoEmpleado::totalMesesUnicosPorEmpresa($id_empresa);

        $paginacion = new Paginacion($pagina_actual, $registro_por_pagina, $total);

        if($paginacion->total_paginas() > 0 && $paginacion->total_paginas() < $pagina_actual){
            header("Location: /admin/bonos/empresa?id={$id_empresa}&page=1");
            return;
        }

        // Obtenemos los meses paginados de la empresa
        $offset = $paginacion->offset();
        $meses = BonoEmpleado::paginarMesesPorEmpresa($id_empresa, $registro_por_pagina, $offset);

        // Buscamos la empresa para mostrar su nombre en la vista si lo deseas
        $empresa = Empresa::find($id_empresa);

        $router->render('admin/bonos/periodos', [
            'titulo' => 'Resumen de Bonos por Mes',
            'empresa' => $empresa,
            'meses' => $meses,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function detalle(Router $router) {
        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        $id_empresa = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $periodo = $_GET['periodo'] ?? '';
        $TipoPeriodos = NominaPeriodo::all();

        if(!$id_empresa || empty($periodo)) {
            header('Location: /admin/bonos');
            return;
        }

        if(!$pagina_actual || $pagina_actual < 1){
            header("Location: /admin/bonos/detalle?id={$id_empresa}&periodo={$periodo}&page=1");
            return;
        }

        $registro_por_pagina = 10; 
        
        // 1. Obtener el total de registros de esta empresa en este periodo específico
        $total = BonoEmpleado::totalRegistrosPorEmpresaYPeriodo($id_empresa, $periodo);

        $paginacion = new Paginacion($pagina_actual, $registro_por_pagina, $total);

        if($paginacion->total_paginas() > 0 && $paginacion->total_paginas() < $pagina_actual){
            header("Location: /admin/bonos/detalle?id={$id_empresa}&periodo={$periodo}&page=1");
            return;
        }

        // 2. Obtener el offset y realizar la consulta con LIMIT y OFFSET
        $offset = $paginacion->offset();

        $query = "SELECT * FROM bono_empleado 
                  WHERE empresa_id = {$id_empresa} AND estatus_id = '1' AND mes_year = '{$periodo}'
                  ORDER BY id DESC
                  LIMIT {$registro_por_pagina} OFFSET {$offset}";
                
        $bonosDelMes = BonoEmpleado::consultarSQL($query);
        $empresa = Empresa::find($id_empresa);
        
        foreach($bonosDelMes as $bono){
            $bono->empleado = Empleado::find($bono->empleado_id);
        }

        $router->render('admin/bonos/bonos', [
            'titulo' => "Bonos del Periodo: {$periodo}",
            'empresa' => $empresa,
            'bonos' => $bonosDelMes,
            'periodo' => $periodo,
            'TipoPeriodos' => $TipoPeriodos,
            'paginacion' => $paginacion->paginacion() 
        ]);
    }

    public static function crear(Router $router){
        $alertas = [];
        $bono = new BonoEmpleado;
        $id_empresa = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if(!$id_empresa){
            header('Location: /admin/bonos');
            return;
        }

        $empleados = Empleado::whereArray(['empresa_id' => $id_empresa]);
        $periodos = NominaPeriodo::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            if(isset($_POST['monto_usd']) && isset($_POST['tasa'])) {
                $_POST['monto_usd'] = str_replace(',', '.', $_POST['monto_usd']);
                $_POST['tasa'] = str_replace(',', '.', $_POST['tasa']);
            }
            $bono->sincronizar($_POST);
            $bono->monto_bs = $_POST['monto_usd'] * $_POST['tasa'];
            $bono->mes_year = substr($_POST['fecha'], 0, 7);
            $bono->empresa_id = $id_empresa;
            $alertas = $bono->validar();
            if(empty($alertas)){
                $resultado = $bono->guardar();
                if($resultado){
                    header("Location: /admin/bonos/empresa?id={$id_empresa}&page=1");
                }
            }
        }

        $router->render('admin/bonos/crear', [
            'titulo' => 'Crear Bono',
            'alertas' => $alertas,
            'periodos' => $periodos,
            'bono' => $bono,
            'empleados' => $empleados
        ]);
    }

    public static function editar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            $bono = BonoEmpleado::find($_POST['bono_id']);
            if(isset($_POST['monto_usd'])) {
                $_POST['monto_usd'] = str_replace(',', '.', $_POST['monto_usd']);
            }
            $bono->monto_bs = $_POST['monto_usd'] * $_POST['tasa'];
            $bono->mes_year = substr($_POST['fecha'], 0, 7);
            $bono->sincronizar($_POST);
            $alertas = $bono->validar();
            if(empty($alertas)){
                $resultado = $bono->guardar();
                echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'Bono editado correctamente'
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

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $bono = BonoEmpleado::find($id);

            if(!empty($bono)){
                $bono->estatus_id = 2;
                $resultado = $bono->guardar();
                if($resultado){
                    echo json_encode([
                    'tipo' => 'exito',
                    'mensaje' => 'El bono se ha eliminado correctamente.'
                ]);
                return;
                }
            } else {
                // Si hay errores de validación
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'No se pudo eliminar el Bono.'
                ]);
                return;
            }
        }        
    }

}