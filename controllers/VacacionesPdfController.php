<?php
namespace Controllers;

use Model\Cargo;
use Model\Departamento;
use Model\Empleado;
use Model\Empresa;
use Model\TipoDocumento;
use Model\Vacaciones;

class VacacionesPdfController{

    public static function reporte(){
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($id_empresa);
        $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);

        $id_vacacion = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $vacacion = Vacaciones::find($id_vacacion);
        $vacacion->departamento = Departamento::find($vacacion->departamento_id);

        $empleado = Empleado::find($vacacion->empleado_id);
        $empleado->cargo = Cargo::find($vacacion->cargoEmpleado_id);
        $empleado->documento = TipoDocumento::where('id', $empleado->documento_id);
        

        ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/vacaciones/reporteEmpleado.php';
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
}