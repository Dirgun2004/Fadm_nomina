<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Banco</title>
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
            margin: 5px 0;
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
            font-size: 14px;
            border: 1px solid #ccc;
            padding: 8px;
        }

        .tabla-nomina td {
            border: 1px solid #ccc; 
            padding: 8px; 
            font-size: 12px;
        }

        .tabla-nomina tr:nth-child(even) {
            background-color: #ece3e3;
        }

        .fila-total td {
            font-weight: bold;
            background-color: #f4f4f4;
        }

        .no-table, .no-table td{
            border: none;
        }
    </style>

</head>
<body>

    <h2><?php echo $empresa->nombre ?></h2>
    <p class="report-subtitle">Rif: <?php echo $empresa->tipo_rif->tipo . '-' . $empresa->rif; ?></p>
    <br>
    <p class="report-banner">RESUMEN BANCO | <?php echo 'PERIODO ' . date("m-Y", strtotime($periodo)); ?></p>
    <br>
        <!-- Tabla de datos principal -->
<table class="tabla-nomina">
            <thead>
                <tr>
                    <th class="text-center" style="width: 25%;">NOMBRES</th>
                    <th class="text-center" style="width: 15%;">CEDULA</th>
                    <th class="text-center" style="width: 20%;">CONCEPTO</th>
                    <th class="text-center" style="width: 20%;">MONTO USD</th>
                    <th class="text-center" style="width: 20%;">MONTO BS</th>
                </tr>
            </thead>
            <tbody>
                <!-- Listado de Empleados -->
                <?php foreach($bonosDelMes as $bono){ ?>
                <tr>
                    <td class="text-left"><?php echo $bono->empleado->nombres . ' ' . $bono->empleado->apellidos; ?></td>
                    <td class="text-center"><?php echo $bono->empleado->tipo->tipo . '-' . number_format($bono->empleado->cedula, 0, '.', '.'); ?></td>
                    <td class="text-center"><?php echo $bono->concepto; ?></td>
                    <td class="text-right">$<?php echo number_format($bono->monto_usd, 2, ',', '.'); ?></td>
                    <td class="text-right">Bs. <?php echo number_format($bono->monto_bs, 2, ',', '.'); ?></td>
                </tr>
                <?php } ?>
                <tr class="no-table">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- Fila de Totales Estilo Bloque -->
                <tr class="fila-totales-bloque">
                    <!-- Celda izquierda unificada con fondo vino tinto y texto grande -->
                    <td rowspan="2" class="text-center celda-totales-titulo" style="background-color: #90232b; color: #ffffff; font-size: 16px; font-weight: bold; vertical-align: middle;">
                        TOTALES
                    </td>
                    <th class="text-center" style="background-color: #90232b; color: #ffffff; font-size: 11px;">EMPLEADOS</th>
                    <th class="text-center" style="background-color: #90232b; color: #ffffff; font-size: 11px;">TOTAL USD</th>
                    <th colspan="2" class="text-center" style="background-color: #90232b; color: #ffffff; font-size: 11px;">TOTAL BS</th>
                </tr>
                <tr>
                    <td class="text-center" style="font-weight: bold; font-size: 12px;"><?php echo $total_empleados; ?></td>
                    <td class="text-right" style="font-weight: bold; font-size: 12px;">$ <?php echo number_format($montoTotalUsd, 2, ',', '.'); ?></td>
                    <td colspan="2" class="text-right" style="font-weight: bold; font-size: 12px;">Bs. <?php echo number_format($montoTotalBs, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

</body>
</html>