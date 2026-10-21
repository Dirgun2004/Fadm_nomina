<?php 

namespace Controllers;

use Model\BonoEmpleado;
use Model\Empleado;
use Model\Empresa;
use Model\TipoDocumento;

class BonoPdfController{

    public static function listadoPeriodo(){
    $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
    $periodo = $_GET['periodo'];
    $empresa = Empresa::find($id_empresa);
    $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);
    
    if(!$id_empresa || !$periodo) {
        header('Location: /admin/bonos');
        return;
    }

    $query = "SELECT b.* 
                FROM bono_empleado b
                JOIN empleados e ON b.empleado_id = e.id
                WHERE e.empresa_id = {$id_empresa} AND b.mes_year = '{$periodo}'
                ORDER BY b.id DESC";
                
        $bonosDelMes = BonoEmpleado::consultarSQL($query);
        foreach($bonosDelMes as $bono){
            $bono->empleado = Empleado::find($bono->empleado_id);
            $bono->empleado->tipo = TipoDocumento::find($bono->empleado->documento_id);
        }

    ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/bonos/reciboGeneral.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 7,
            'margin_bottom' => 5,
            'margin_left' => 9,
            'margin_right' => 9,
            'tempDir' => sys_get_temp_dir()
        ]);

        // 4. Escribir el HTML y generar la salida
        $mpdf->WriteHTML($html);
        
        // 'I' muestra en línea en el navegador, 'D' fuerza la descarga directa
        $mpdf->Output('reporte_empresas.pdf', 'I');
    }

    public static function banco(){
    $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
    $periodo = $_GET['periodo'];
    $empresa = Empresa::find($id_empresa);
    $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);
    $total_empleados = 0;
    $montoTotalUsd = 0;
    $montoTotalBs = 0;
    if(!$id_empresa || !$periodo) {
        header('Location: /admin/bonos');
        return;
    }

    $query = "SELECT b.* 
                FROM bono_empleado b
                JOIN empleados e ON b.empleado_id = e.id
                WHERE e.empresa_id = {$id_empresa} AND b.mes_year = '{$periodo}'
                ORDER BY b.id DESC";
                
        $bonosDelMes = BonoEmpleado::consultarSQL($query);
        foreach($bonosDelMes as $bono){
            $bono->empleado = Empleado::find($bono->empleado_id);
            $bono->empleado->tipo = TipoDocumento::find($bono->empleado->documento_id);
            $total_empleados++;
            $montoTotalUsd += $bono->monto_usd;
            $montoTotalBs += $bono->monto_bs;
        }

    ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/bonos/banco.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 7,
            'margin_bottom' => 5,
            'margin_left' => 9,
            'margin_right' => 9,
            'tempDir' => sys_get_temp_dir()
        ]);

        // 4. Escribir el HTML y generar la salida
        $mpdf->WriteHTML($html);
        
        // 'I' muestra en línea en el navegador, 'D' fuerza la descarga directa
        $mpdf->Output('reporte_empresas.pdf', 'I');
    }

    public static function recibo(){
    $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
    $bonoId = $_GET['id'];
    $empresa = Empresa::find($id_empresa);
    $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);
    
    if(!$id_empresa || !$bonoId) {
        header('Location: /admin/bonos');
        return;
    }

    $bono = BonoEmpleado::find($bonoId);
    $empleado = Empleado::find($bono->empleado_id);
    $empleado->tipo_documento = TipoDocumento::find($empleado->documento_id);

    ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/bonos/recibo.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 7,
            'margin_bottom' => 5,
            'margin_left' => 9,
            'margin_right' => 9,
            'tempDir' => sys_get_temp_dir()
        ]);

        // 4. Escribir el HTML y generar la salida
        $mpdf->WriteHTML($html);
        
        // 'I' muestra en línea en el navegador, 'D' fuerza la descarga directa
        $mpdf->Output('reporte_empresas.pdf', 'I');
    }

}