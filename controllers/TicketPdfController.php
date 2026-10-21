<?php
namespace Controllers;

use Model\Cargo;
use Model\Cestaticket;
use Model\CestaticketEmpleado;
use Model\Empleado;
use Model\Empresa;
use Model\TipoDocumento;

class TicketPdfController{

    public static function reporteGeneral(){
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($id_empresa);
        $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);

        $idCestaticket = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $cestaticket = Cestaticket::find($idCestaticket);

        ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/cestaticket/reporteGeneral.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 5,
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

    public static function recibos(){
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($id_empresa);
        $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);

        $idCestaticket = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $cestaticket = Cestaticket::find($idCestaticket);

        $empleadosCestaticket = CestaticketEmpleado::whereArray(['cestaticket_id' => $idCestaticket]);

        foreach($empleadosCestaticket as $empleado){
            $empleado->empleado = Empleado::find($empleado->empleado_id);
            $empleado->cargo = Cargo::find($empleado->cargo_empleado);
            $empleado->tipo_documento = TipoDocumento::find($empleado->empleado->documento_id);
        }

        ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/cestaticket/recibos.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 9,
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
        $empresa = Empresa::find($id_empresa);
        $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);

        $idCestaticket = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $cestaticket = Cestaticket::find($idCestaticket);

        $empleadosCestaticket = CestaticketEmpleado::whereArray(['cestaticket_id' => $idCestaticket]);

        foreach($empleadosCestaticket as $empleado){
            $empleado->empleado = Empleado::find($empleado->empleado_id);
            $empleado->cargo = Cargo::find($empleado->cargo_empleado);
            $empleado->tipo_documento = TipoDocumento::find($empleado->empleado->documento_id);
        }

        ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/cestaticket/reporteBanco.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 9,
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