<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Nomina</title>
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
    </style>

</head>
<body>

    <h2><?php echo $empresa->nombre ?></h2>
    <p class="report-subtitle">Rif: <?php echo $empresa->tipo_rif->tipo . '-' . $empresa->rif; ?></p>
    <br>
    <p class="report-banner">RESUMEN NOMINA | <?php echo 'DESDE EL ' . date("d-m-Y", strtotime($nomina->fecha_inicio)) . ' HASTA EL ' . date("d-m-Y", strtotime($nomina->fecha_fin));; ?></p>
    <br>
        <!-- Tabla de datos principal -->
        <table class="tabla-nomina">
            <thead>
                <tr>
                    <th class="text-left" style="width: 50%;">CONCEPTO</th>
                    <th class="text-center" style="width: 25%;">ASIGNACIÓN</th>
                    <th class="text-center" style="width: 25%;">DEDUCCIÓN</th>
                </tr>
            </thead>
            <tbody>
                <!-- Asignaciones -->
                <tr>
                    <td>SUELDOS Y SALARIOS</td>
                    <td class="text-center"><?php echo number_format($nomina->resumen_sueldos, 2, ',', '.'); ?></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td>SÁBADO EN DESCANSO</td>
                    <td class="text-center"><?php echo number_format($nomina->resumen_sabados, 2, ',', '.'); ?></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td>DOMINGO EN DESCANSO</td>
                    <td class="text-center"><?php echo number_format($nomina->resumen_domingos, 2, ',', '.'); ?></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td>FERIADOS</td>
                    <td class="text-center"><?php echo number_format($nomina->resumen_feriados, 2, ',', '.'); ?></td>
                    <td class="text-center"></td>
                </tr>

                <!-- Deducciones / Retenciones Legales -->
                <tr>
                    <td>F.A.O.V. (<?php echo number_format($nomina->faov->porcentaje, 0); ?>%)</td>
                    <td class="text-center"></td>
                    <td class="text-center"><?php echo number_format($nomina->resumen_faov, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>SEGURO SOCIAL (<?php echo number_format($nomina->ivss->porcentaje, 0); ?>%)</td>
                    <td class="text-center"></td>
                    <td class="text-center"><?php echo number_format($nomina->resumen_ivss, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>PÉRDIDA INVOLUNTARIA DEL EMPLEO (<?php echo $nomina->spf->porcentaje; ?>%)</td>
                    <td class="text-center"></td>
                    <td class="text-center"><?php echo number_format($nomina->resumen_spf, 2, ',', '.'); ?></td>
                </tr>

                <!-- Fila de Totales -->
                <tr class="fila-total">
                    <td>TOTAL</td>
                    <td class="text-right"><?php echo number_format($nomina->resumenTotal_asignacion, 2, ',', '.'); ?></td> // Asignacion
                    <td class="text-right"><?php echo number_format($nomina->resumenTotal_deduccion, 2, ',', '.'); ?></td> // Deduccion
                </tr>

                <tr class="fila-total">
                    <td>TOTAL TRABAJADORES</td>
                    <td class="text-center" colspan="2"><?php echo $nomina->total_empleados; ?></td>
                </tr>
            </tbody>
        </table>

        <p class="net-total-badge">Total (asignaciones - deducciones)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;bs: <span><?php echo number_format($nomina->resumen_total, 2, ',', '.'); ?></span></p>

</body>
</html>