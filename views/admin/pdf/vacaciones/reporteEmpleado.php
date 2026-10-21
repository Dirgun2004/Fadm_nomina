<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Vacacion</title>
    <style>
        /* Estilos generales */
        body { font-family: Arial, sans-serif; color: #333; font-size: 10px; }
        h2 { text-align: center; color: #90232b; font-size: 25px; margin: 0; }
        h3 { text-align: center; margin: 10px 0px;  }
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
            margin: 5px;
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
            font-size: 12px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .tabla-nomina td {
            border: 1px solid #ccc; 
            padding: 7px; 
            font-size: 10px;
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
        .firma-table {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .texto-legal {
            font-size: 10px;
            color: #333;
            line-height: 1.2;
            text-transform: uppercase;
        }
        .contenedor-firma{
            margin-top: 50px;
        }
        .linea-firma {
            width: 30%;
            margin: 0 auto 5px auto;
            border-bottom: 1px solid #333;
            text-align: center;
        }
        .etiqueta-firma {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            color: #333;
            padding-top: 6px;
        }
    </style>

</head>
<body>

    <h2><?php echo $empresa->nombre; ?></h2>
    <p class="report-subtitle">Rif: <?php echo $empresa->tipo_rif->tipo . '-' . $empresa->rif; ?></p>
    <br>
    <p class="report-banner">RECIBO DE PAGO DE VACACIONES</p>
    <br>

    <h3>DATOS DEL EMPLEADO</h3>
        <table class="pdf_table1">
            <tbody>
                <tr>
                    <td class="pdf_header">
                        <span class="pdf_header-span">NOMBRE Y APELLIDO</span>
                        <p class="pdf_header-p"><?php echo $empleado->nombres . ' ' . $empleado->apellidos; ?></p>
                    </td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">CÉDULA</span>
                        <p class="pdf_header-p"><?php echo $empleado->documento->tipo . '-' . number_format($empleado->cedula, 0, ',', '.') ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="pdf_header">
                        <span class="pdf_header-span">CARGO</span>
                        <p class="pdf_header-p"><?php echo $empleado->cargo->nombre; ?></p>
                    </td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">DEPARTAMENTO</span>
                        <p class="pdf_header-p"><?php echo $vacacion->departamento->nombre; ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="pdf_header">
                        <span class="pdf_header-span">INGRESO</span>
                        <p class="pdf_header-p"><?php echo date("d-m-Y", strtotime($empleado->ingreso)); ?></p>
                    </td>
                    <td class="pdf_header">
                    </td>
                </tr>
            </tbody>
        </table>    
    <h3 class="text-center">DATOS DE LA VACACION</h3>
        <table class="pdf_table1">
            <tbody>
                <tr>
                    <td class="pdf_header" >
                        <span class="pdf_header-span">DÍAS SOLICITADOS</span>
                        <p class="pdf_header-p"><?php echo $vacacion->dias_solicitados; ?></p>
                    </td>
                    <td class="pdf_header"></td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">DIAS TOTALES</span>
                        <p class="pdf_header-p"><?php echo $vacacion->total_dias; ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="pdf_header">
                        <span class="pdf_header-span">INICIO DEL DISFRUTE</span>
                        <p class="pdf_header-p"><?php echo date("d-m-Y", strtotime($vacacion->fecha_inicio)); ?></p>
                    </td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">FIN DEL DISFRUTE</span>
                        <p class="pdf_header-p"><?php echo date("d-m-Y", strtotime($vacacion->fecha_fin)); ?></p>
                    </td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">REINTEGRO</span>
                        <p class="pdf_header-p"><?php echo date("d-m-Y", strtotime($vacacion->fecha_reintegro)); ?></p>
                    </td>
                </tr>

                <tr>
                    <td class="pdf_header">
                        <span class="pdf_header-span">BASICO MENSUAL</span>
                        <p class="pdf_header-p">Bs. <?php echo number_format($vacacion->sueldo_mensual, 2, ',', '.'); ?></p>
                    </td>                    
                    <td class="pdf_header">
                        <span class="pdf_header-span">VARIABLE MENSUAL</span>
                        <p class="pdf_header-p">Bs. <?php echo number_format($vacacion->sueldo_variable_mensual, 2, ',', '.'); ?></p>
                    </td>                    
                    <td class="pdf_header">
                        <span class="pdf_header-span">PROMEDIO MENSUAL</span>
                        <p class="pdf_header-p">Bs. <?php echo number_format($vacacion->sueldo_mensual_promedio, 2, ',', '.'); ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="pdf_header">
                        <span class="pdf_header-span">BASICO DIARIO</span>
                        <p class="pdf_header-p">Bs. <?php echo number_format($vacacion->sueldo_diario, 2, ',', '.'); ?></p>
                    </td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">VARIABLE DIARIO</span>
                        <p class="pdf_header-p">Bs. <?php echo number_format($vacacion->sueldo_variable_diario, 2, ',', '.'); ?></p>
                    </td>
                    <td class="pdf_header">
                        <span class="pdf_header-span">PROMEDIO DIARIO</span>
                        <p class="pdf_header-p">Bs. <?php echo number_format($vacacion->sueldo_diario_promedio, 2, ',', '.'); ?></p>
                    </td>

                </tr>
            </tbody>
        </table>    

        <table class="tabla-nomina">
            <thead>
                <tr>
                    <th class="text-center">PERIODOS</th>
                    <th class="text-center">TOTALES</th>
                    <th class="text-center">DISPONIBLES</th>
                    <th class="text-center">DISFRUTADOS</th>
                    <th class="text-center">SOLICITADOS</th>
                    <th class="text-center">RESTANTES</th>
                </tr>
                <tbody>
                    <tr>
                        <td class="text-center"><?php echo $vacacion->periodo; ?></td>
                        <td class="text-center"><?php echo $vacacion->dias_solicitados; ?></td>
                        <td class="text-center"><?php echo $vacacion->dias_disponibles; ?></td>
                        <td class="text-center"><?php echo $vacacion->dias_solicitados; ?></td>
                        <td class="text-center"><?php echo $vacacion->dias_solicitados; ?></td>
                        <td class="text-center"><?php echo $vacacion->dias_restantes ?></td>
                    </tr>
                </tbody>
            </thead>
        </table>

        <!-- Tabla de datos principal -->
        <table class="tabla-nomina">
            <thead>
                <tr>
                    <th class="text-left" style="width: 21%;">ASIGNACION</th>
                    <th class="text-center" style="width: 14%;">DIAS</th>
                    <th class="text-center" style="width: 14%;">MONTO</th>
                    <th class="text-left" style="width: 21%;">DEDUCCION</th>
                    <th class="text-center" style="width: 14%;">DIAS</th>
                    <th class="text-center" style="width: 14%;">MONTO</th>
                </tr>
            </thead>
            <tbody>
                <!-- Asignaciones -->
                <tr>
                    <td>Días habiles Art. 190</td>
                    <td class="text-center"><?php echo $vacacion->dias_habiles; ?></td>
                    <td class="text-center">Bs. <?php echo number_format($vacacion->asignacion_habiles, 2, ',', '.'); ?></td>
                    <td>Días anticipados</td>
                    <td class="text-center"><?php echo $vacacion->dias_anticipados; ?></td>
                    <td class="text-right">Bs. <?php echo number_format($vacacion->deduccion_diasAnticipados, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Días adicionales Art. 190</td>
                    <td class="text-center"><?php echo $vacacion->dias_adicionales; ?></td>
                    <td class="text-center">Bs. <?php echo number_format($vacacion->asignacion_diaAdicional, 2, ',', '.'); ?></td>
                    <td>Días bono anticipados</td>
                    <td class="text-center"><?php echo $vacacion->dias_bonoAnticipado; ?></td>
                    <td class="text-right">Bs. <?php echo number_format($vacacion->deduccion_bonoAnticipado, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Bono vacacional Art. 192</td>
                    <td class="text-center"><?php echo $vacacion->dias_solicitados; ?></td>
                    <td class="text-center">Bs. <?php echo number_format($vacacion->asignacion_bonoVacacional, 2, ',', '.'); ?></td>
                    <td>I.S.L.R</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-right">Bs. <?php echo number_format($vacacion->deduccion_islr, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Días bono adicional Art. 192</td>
                    <td class="text-center"><?php echo $vacacion->dias_adicionales; ?></td>
                    <td class="text-center">Bs. <?php echo number_format($vacacion->asignacion_adicionales, 2, ',', '.'); ?></td>
                    <td>I.V.S.S.</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-right">Bs. <?php echo number_format($vacacion->deduccion_ivss, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Días feriados</td>
                    <td class="text-center"><?php echo $vacacion->dias_feriados; ?></td>
                    <td class="text-center">Bs. <?php echo number_format($vacacion->asignacion_feriados, 2, ',', '.'); ?></td>
                    <td>R.P.E.</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-right">Bs. <?php echo number_format($vacacion->deduccion_rpe, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Días de Descanso</td>
                    <td class="text-center"><?php echo $vacacion->dias_descanso; ?></td>
                    <td class="text-center">Bs. <?php echo number_format($vacacion->asignacion_descanso, 2, ',', '.'); ?></td>
                    <td>R.P.V.H.</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-right">Bs. <?php echo number_format($vacacion->deduccion_rpvh, 2, ',', '.'); ?></td>
                </tr>

                <tr>
                    <td class="no-border">&nbsp;</td>
                    <td class="no-border">&nbsp;</td>
                    <td class="no-border">&nbsp;</td>
                    <td class="no-border">&nbsp;</td>
                    <td class="no-border">&nbsp;</td>
                    <td class="no-border">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">TOTAL ASIGNACIONES</td>
                    <td class="text-right">Bs. <?php echo number_format($vacacion->asignacion_total, 2, ',', '.'); ?></td>

                    <td colspan="2">TOTAL DEDUCCIONES</td>
                    <td class="text-right">Bs. <?php echo number_format($vacacion->deduccion_total, 2, ',', '.'); ?></td>
                </tr>

            </tbody>
        </table>

        <p class="net-total-badge">Total (asignaciones - deducciones)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;bs: <?php echo number_format($vacacion->asignacion_deduccion_total, 2, ',', '.'); ?></p>

        <div class="firma_table">
            <p class="texto-legal"><?php echo '<strong>YO, ' . $empleado->nombres . ' ' . $empleado->apellidos . ',  DECLARO HABER RECIBIDO DE: </strong>' . $empresa->nombre . ' RIF: ' . $empresa->tipo_rif->tipo . '-' . $empresa->rif . '  EL TOTAL NETO SEÑALADO (bs:' . number_format($vacacion->asignacion_deduccion_total, 2, ',', '.') . ') POR CONCEPTO DE VACACIONES CORRESPONDIENTES AL PERIODO ' . $vacacion->periodo; ?></p>

            

            <div class="contenedor-firma">
                <div class="linea-firma">&nbsp;</div>
                <div class="etiqueta-firma">FIRMA DEL EMPLEADO</div>
            </div>
        </div>
    </div>
</body>
</html>