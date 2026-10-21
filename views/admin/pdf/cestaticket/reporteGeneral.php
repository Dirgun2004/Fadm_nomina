<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Cestaticket</title>
    <style>
        /* Estilos generales */
        body { font-family: Arial, sans-serif; color: #333; font-size: 10px; }
        h2 { text-align: center; color: #90232b; font-size: 25px; margin: 0; }

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
        }

        /* Contenedor principal de cada empleado */
        .table-master {
            page-break-inside: avoid;
            margin-bottom: 20px;
            padding: 5px 10px; 
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
            padding: 10px; 
            background-color: #90232b; 
            text-align: right; 
            border-radius: 5px; 
            color: white; 
            font-weight: bold; 
            text-transform: uppercase; 
            margin: 10px 0 5px 0; 
            font-size: 15px;
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
            font-size: 15px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .tabla-nomina td {
            border: 1px solid #ccc; 
            padding: 7px; 
            font-size: 15px;
        }

        .tabla-nomina tr:nth-child(even) {
            background-color: #ece3e3;
        }

        .fila-total td {
            font-weight: bold;
            background-color: #f4f4f4;
            
        }

        tr .no-border-right{
            border-right: none;
        }

        tr .no-border{
            border: none;
        }
    </style>

</head>
<body>

    <h2><?php echo $empresa->nombre ?></h2>
    <p class="report-subtitle">Rif: <?php echo $empresa->tipo_rif->tipo . '-' . $empresa->rif; ?></p>
    <br>
    <p class="report-banner">RESUMEN CESTATICKET | <?php echo 'DESDE EL ' . date("d-m-Y", strtotime($cestaticket->periodo_inicio)) . ' HASTA EL ' . date("d-m-Y", strtotime($cestaticket->periodo_fin)); ?></p>
    <br>
        <!-- Tabla de datos principal -->
        <table class="tabla-nomina">
            <thead>
                <tr>
                    <th class="text-left" style="width: 40%;">CONCEPTO</th>
                    <th class="text-center" style="width: 10%;">TOTAL DÍAS</th>
                    <th class="text-center" style="width: 25%;">ASIGNACIÓN</th>
                    <th class="text-center" style="width: 25%;">DEDUCCIÓN</th>
                </tr>
            </thead>
            <tbody>
                <!-- Asignaciones -->
                <tr>
                    <td>TOTAL MONTO DIAS CESTATICKET</td>
                    <td class="text-center"><?php echo $cestaticket->periodo_dias; ?></td>
                    <td class="text-center">Bs. <?php echo number_format($cestaticket->total_asignacion, 2, ',', '.'); ?></td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td>TOTAL MONTO DIAS NO TRABAJADOS</td>
                    <td class="text-center"><?php echo $cestaticket->total_faltas; ?></td>
                    <td class="text-right"></td>
                    <td class="text-center">Bs. <?php echo number_format($cestaticket->total_deduccion, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td class="no-border" colspan="2">&nbsp;</td>
                    <td class="no-border" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="no-border-right" style="width: 35%;">TOTAL TRABAJADORES: </td>
                    <td class="text-center" style="width: 15%; border-left: none;"><?php echo $cestaticket->total_trabajadores; ?></td>
                    <td class="no-border-right" style="width: 35%;">TASA PROMEDIO:</td>
                    <td class="text-center" style="width: 15%; border-left: none;"><?php echo number_format($cestaticket->tasa, 2, ',', '.'); ?></td>
                </tr>

            </tbody>
        </table>

        <p class="net-total-badge">Total (asignaciones - deducciones)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;bs: <span><?php echo number_format($cestaticket->total_calculo, 2, ',', '.'); ?></span></p>
    </div>
</body>
</html>