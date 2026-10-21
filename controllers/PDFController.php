<?php
namespace Controllers;

use Model\Cargo;
use Model\Empleado;
use Model\Empresa;
use Model\Estatus;
use Model\Nomina;
use Model\NominaEmpleado;
use Model\ParametrosLegales;
use Model\Sueldo_minimo;
use Model\TipoDocumento;
use MVC\Router;

class PDFController{

    public static function generarPdf(){
        // Obtener la nomina principal
        $id_nomina = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $nomina = Nomina::find($id_nomina);
        $nomina->sueldo_minimo = Sueldo_minimo::find($nomina->salario_minimo);
        // Obtener la empresa
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($id_empresa);
        $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);

        // Obtener la nomina de cada empleado
        $nominas_empleados = NominaEmpleado::whereArray(['nomina_id' => $id_nomina]);
        foreach($nominas_empleados as $nominaEmp){
            $nominaEmp->empleado = Empleado::find($nominaEmp->empleado_id);
            $nominaEmp->cargo = Cargo::find($nominaEmp->cargo_id);
            $nominaEmp->tipo_documento = TipoDocumento::find($nominaEmp->empleado->documento_id);
            $nominaEmp->empleado_estatus = Estatus::find($nominaEmp->estatus_empleado);
        }

        // Faov
        $faov = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'FAOV' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
        $faovActivo = !empty($faov) ? array_shift($faov) : null;
        // Ivss
        $ivss = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'IVSS' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
        $ivssActivo = !empty($ivss) ? array_shift($ivss) : null;
        // SPF
        $spf = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'SPF' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
        $spfActivo = !empty($spf) ? array_shift($spf) : null;

        // 2. Usar búfer de salida para capturar la vista del PDF
        ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/nomina/nominaDetalle.php';
        $html = ob_get_clean();

        // 3. Instanciar mPDF (asegurate de haber ejecutado composer require mpdf/mpdf)
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
    public static function reciboPdf(){
        // Obtener la nomina principal
        $id_nomina = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $nomina = Nomina::find($id_nomina);
        $nomina->sueldo_minimo = Sueldo_minimo::find($nomina->salario_minimo);
        // Obtener la empresa
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($id_empresa);
        $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);

        // Obtener la nomina de cada empleado
        $nominas_empleados = NominaEmpleado::whereArray(['nomina_id' => $id_nomina]);
        foreach($nominas_empleados as $nominaEmp){
            $nominaEmp->empleado = Empleado::find($nominaEmp->empleado_id);
            $nominaEmp->cargo = Cargo::find($nominaEmp->cargo_id);
            $nominaEmp->tipo_documento = TipoDocumento::find($nominaEmp->empleado->documento_id);
            $nominaEmp->empleado_estatus = Estatus::find($nominaEmp->estatus_empleado);
        }

        // Faov
        $faov = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'FAOV' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
        $faovActivo = !empty($faov) ? array_shift($faov) : null;
        // Ivss
        $ivss = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'IVSS' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
        $ivssActivo = !empty($ivss) ? array_shift($ivss) : null;
        // SPF
        $spf = ParametrosLegales::consultarSQL("SELECT * FROM parametros_legales WHERE nombre_concepto = 'SPF' ORDER BY fecha_fin IS NULL DESC, fecha_inicio DESC LIMIT 1");
        $spfActivo = !empty($spf) ? array_shift($spf) : null;

        // 2. Usar búfer de salida para capturar la vista del PDF
        ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/nomina/reciboEmpleado.php';
        $html = ob_get_clean();

        // 3. Instanciar mPDF (asegurate de haber ejecutado composer require mpdf/mpdf)
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 8,
            'margin_left' => 9,
            'margin_right' => 9,
            'tempDir' => sys_get_temp_dir()
        ]);

        // 4. Escribir el HTML y generar la salida
        $mpdf->WriteHTML($html);
        
        // 'I' muestra en línea en el navegador, 'D' fuerza la descarga directa
        $mpdf->Output('reporte_empresas.pdf', 'I');

    }
    public static function resumenPdf(){
        // Obtener la nomina principal
        $id_nomina = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $nomina = Nomina::find($id_nomina);
        $nomina->sueldo_minimo = Sueldo_minimo::find($nomina->salario_minimo);
        // Obtener la empresa
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($id_empresa);
        $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);

        // Obtener la nomina de cada empleado
        $nominas_empleados = NominaEmpleado::whereArray(['nomina_id' => $id_nomina]);

        $nomina->faov = ParametrosLegales::find($nomina->porcentaje_faov);
        $nomina->ivss = ParametrosLegales::find($nomina->porcentaje_ivss);
        $nomina->spf = ParametrosLegales::find($nomina->porcentaje_spf);
        
        // 2. Usar búfer de salida para capturar la vista del PDF
        ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/nomina/resumenNomina.php';
        $html = ob_get_clean();

        // 3. Instanciar mPDF (asegurate de haber ejecutado composer require mpdf/mpdf)
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 8,
            'margin_left' => 9,
            'margin_right' => 9,
            'tempDir' => sys_get_temp_dir()
        ]);

        // 4. Escribir el HTML y generar la salida
        $mpdf->WriteHTML($html);
        
        // 'I' muestra en línea en el navegador, 'D' fuerza la descarga directa
        $mpdf->Output('reporte_empresas.pdf', 'I');

    }

    public static function bancoNominaPdf(){
        // Obtener la nomina principal
        $id_nomina = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $nomina = Nomina::find($id_nomina);
        $nomina->sueldo_minimo = Sueldo_minimo::find($nomina->salario_minimo);
        // Obtener la empresa
        $id_empresa = filter_var($_GET['empresa'], FILTER_VALIDATE_INT);
        $empresa = Empresa::find($id_empresa);
        $empresa->tipo_rif = TipoDocumento::find($empresa->documento_id);

        // Obtener la nomina de cada empleado
        $nominas_empleados = NominaEmpleado::whereArray(['nomina_id' => $id_nomina]);

        foreach($nominas_empleados as $nominaEmp){
            $nominaEmp->empleado = Empleado::find($nominaEmp->empleado_id);
            $nominaEmp->cargo = Cargo::find($nominaEmp->cargo_id);
            $nominaEmp->tipo_documento = TipoDocumento::find($nominaEmp->empleado->documento_id);
        }


        // 2. Usar búfer de salida para capturar la vista del PDF
        ob_start();
        // Pasamos las variables necesarias mediante extract o incluimos directamente
        include_once __DIR__ . '/../views/admin/pdf/nomina/resumenBanco.php';
        $html = ob_get_clean();

        // 3. Instanciar mPDF (asegurate de haber ejecutado composer require mpdf/mpdf)
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 8,
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