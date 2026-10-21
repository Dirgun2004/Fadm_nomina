<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibos Empleados</title>
    <style>
        /* Estilos generales */
        body { font-family: Arial, sans-serif; color: #333; font-size: 10px; }
        h2 { text-align: center; color: #90232b; font-size: 18px; margin: 0; }

        /* Utilidades de texto y alineación */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        /* Cabeceras principales del reporte */
        .report-subtitle {
            text-align: center; 
            text-transform: uppercase; 
            font-weight: bold; 
            margin: 0; 
            font-size: 15px;
            color: #333;
        }

        .report-banner {
            padding: 10px; 
            background-color: #d7d7d7; 
            text-align: center; 
            border-radius: 5px; 
            font-weight: bold; 
            color: #333; 
            border: 1px solid #bbbbbb; 
            font-size: 15px;
            margin: 5px 0;
        }

        /* Contenedor principal de cada empleado */
        .table-master {
            
            margin-bottom: 10px;
            padding: 10px 8px; 
            border: 1px solid #bbbbbb; 
            border-radius: 10px;
        }

        /* Banner de ingreso del empleado */
        .employee-badge {
            padding: 5px; 
            background-color: #90232b; 
            text-align: center; 
            border-radius: 5px; 
            color: white; 
            font-weight: bold; 
            text-transform: uppercase; 
            margin: 5px; 
            font-size: 11px;
        }

        /* Banner inferior de total neto */
        .net-total-badge {
            padding: 5px; 
            background-color: #90232b; 
            text-align: right; 
            border-radius: 5px; 
            color: white; 
            font-weight: bold; 
            text-transform: uppercase; 
            margin: 5px; 
            font-size: 11px;
        }

        /* Tablas de datos personales del empleado */
        .pdf_table1 {
            width: 100%;
            border-collapse: collapse;
        }
            
        .pdf_header {
            border: none;
            border-bottom: 1px solid #bbbbbb;
        }

        .pdf_header-p {
            display: block;
            font-weight: bold; 
            padding: 10px 0px;
            font-size: 10px;
            margin: 0;
        }

        .pdf_header-span {
            font-size: 9px;
            color: #555;
        }

        /* Tabla de conceptos de nómina */
        .tabla-nomina {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tabla-nomina th {
            background-color: #90232b; 
            color: #ffffff;
            font-size: 12px;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .tabla-nomina td {
            border: 1px solid #ccc; 
            padding: 5px; 
            font-size: 10px;
        }

        .tabla-nomina tr:nth-child(even) {
            background-color: #ece3e3;
        }

        .fila-total td {
            font-weight: bold;
            background-color: #f4f4f4;
        }

        /* Estilos para la sección de firmas y textos legales */
        .firma-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .firma-table td {
            border: none;
            padding: 0;
            vertical-align: bottom;
        }
        .texto-legal {
            font-size: 8.5px;
            color: #333;
            line-height: 1.2;
            text-transform: uppercase;
        }
        .linea-firma {
            width: 80%;
            margin: 0 auto 5px auto;
            border-bottom: 1px solid #333;
            text-align: center;
        }
        .etiqueta-firma {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            color: #333;
            padding-top: 6px;
            border-top: 1px solid #333;
        }

        /* Línea de corte inferior estilo ticket */
        .linea-corte {
            margin: 20px;
            border-bottom: 2px dashed #333;
            text-align: right;
            position: relative;
        }
        .icono-tijera {
            font-size: 14px;
            background-color: #fff;
            padding-left: 5px;
            position: relative;
            top: 8px;
        }

        .cortar{
            page-break-before: always;
        }
    </style>

</head>
<body>
    
    <div class="table-master">
        <h2><?php echo $empresa->nombre ?></h2>
        <p class="report-subtitle">Rif: <?php echo $empresa->tipo_rif->tipo . '-' . $empresa->rif; ?></p>
        
        <p class="employee-badge">RECIBO</p>

        <table class="pdf_table1">
            <tbody>
                <tr>
                    <td class="pdf_header">
                        <span class="pdf_header-span">NOMBRE Y APELLIDO</span>
                        <p class="pdf_header-p"><?php echo $empleado->nombres . ' ' . $empleado->apellidos; ?></p>
                    </td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">CÉDULA</span>
                        <p class="pdf_header-p"><?php echo $empleado->tipo_documento->tipo . '-' . number_format($empleado->cedula, 0, '.', '.'); ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="pdf_header">
                    </td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">FECHA</span>
                        <p class="pdf_header-p">$ <?php echo $bono->fecha; ?></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Tabla de datos principal -->
        <table class="tabla-nomina">
            <thead>
                <tr>
                    <th class="text-left" style="width: 40%;">CONCEPTO</th>
                    <th class="text-center" style="width: 20%;">MONTO USD</th>
                    <th class="text-center" style="width: 20%;">TASA BCV</th>
                    <th class="text-center" style="width: 20%;">MONTO BS</th>
                </tr>
            </thead>
            <tbody>
                <!-- Asignaciones -->
                <tr>
                    <td><?php echo $bono->concepto; ?></td>
                    <td class="text-center"><?php echo number_format($bono->monto_usd, 2, ',', '.');; ?></td>
                    <td class="text-center"><?php echo number_format($bono->tasa, 2, ',', '.'); ?></td>
                    <td class="text-center">Bs. <?php echo number_format($bono->monto_bs, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <p class="net-total-badge">Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs: <span><?php echo number_format($bono->monto_bs, 2, ',', '.'); ?></span></p>


        <table class="firma-table">
            <tr>
                <!-- Columna Izquierda: Texto legal -->
                <td style="width: 55%;">
                    <span class="texto-legal">
                        <strong>RECIBÍ DE:</strong> <?php echo $empresa->nombre; ?><br>
                        <em>EL TOTAL NETO SEÑALADO Y ESTOY DE ACUERDO CON LO INDICADO EN ESTE RECIBO.</em>
                    </span>
                </td>
                
                <!-- Columna Derecha: Línea de Firma -->
                <td style="width: 45%; text-align: center;">
                    <div class="linea-firma">&nbsp;</div>
                    <div class="etiqueta-firma">FIRMA DEL EMPLEADO</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="linea-corte">
    </div>
</body>
</html>