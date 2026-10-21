<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function pagina_actual($path){
    return str_contains( $_SERVER['PATH_INFO'] ?? '/', $path) ? true : false;
}

function isAuth() : void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['login'])) {
        header('Location: /login-admin');
        exit; // Detiene la ejecución inmediatamente para evitar que cargue la vista protegida
    }
}

function is_admin() : bool {
    if(!isset($_SESSION)){
        session_start();
    }
    return isset( $_SESSION['admin']) && !empty($_SESSION['admin']);
}

function aos_animation(){
    $efectos = ['fade-up', 'fade-down', 'fade-right', 'fade-left', 'flip-left', 'flip-right', 'zoom-in', 'zoom-in-up', 'zoom-in-down', 'zoom-out'];
    $efecto = array_rand($efectos, 1);
    echo ' data-aos="' . $efectos[$efecto] . '" ';
}


function calcularNominas($fechaInicio, $fechaFin, $arrayFeriados = [], $arrayVacaciones = [], $arrayFaltas = []) {
    $inicio = new \DateTime($fechaInicio);
    $fin = new \DateTime($fechaFin);
    $fin->modify('+1 day'); // Rango inclusivo

    $intervalo = new \DateInterval('P1D');
    $periodo = new \DatePeriod($inicio, $intervalo, $fin);

    // Contadores base limpios
    $diasHabiles = 0; 
    $totalLunes = 0;
    $totalSabados = 0;
    $totalDomingos = 0;
    $feriadosEnRango = 0;
    $diasVacacionesEnRango = 0; 
    $faltas = 0;

    // FASE 1: Conteo puro de los días que tiene el periodo de la nómina
    foreach ($periodo as $fecha) {
        $fechaString = $fecha->format('Y-m-d');
        $diaSemana = (int) $fecha->format('N'); // 1 = Lunes, ..., 7 = Domingo
        
        if (in_array($fechaString, $arrayFaltas)) {
            $faltas++;
        }

        switch ($diaSemana) {
            case 1: 
                $totalLunes++; 
                $diasHabiles++; // El lunes también es un día hábil (lunes a viernes)
                break;
            case 2: case 3: case 4: case 5: 
                $diasHabiles++; 
                break;
            case 6: 
                $totalSabados++; 
                break;
            case 7: 
                $totalDomingos++; 
                break;
        }
    }

    // FASE 2: Restar los días que caigan dentro de las VACACIONES
    foreach ($arrayVacaciones as $fechaVacacion) {
        if ($fechaVacacion >= $fechaInicio && $fechaVacacion <= $fechaFin) {
            $diasVacacionesEnRango++;
            $dtVacacion = new \DateTime($fechaVacacion);
            $diaVacacionSemana = (int) $dtVacacion->format('N');

            switch ($diaVacacionSemana) {
                case 1: 
                    if ($totalLunes > 0) $totalLunes--; 
                    if ($diasHabiles > 0) $diasHabiles--; // Resta de hábiles porque el lunes lo es
                    break;
                case 2: case 3: case 4: case 5: 
                    if ($diasHabiles > 0) $diasHabiles--; 
                    break;
                case 6: 
                    if ($totalSabados > 0) $totalSabados--; 
                    break;
                case 7: 
                    if ($totalDomingos > 0) $totalDomingos--; 
                    break;
            }
        }
    }

    // FASE 3: Restar los días que caigan dentro de los FERIADOS
    foreach ($arrayFeriados as $fechaFeriado) {
        if ($fechaFeriado >= $fechaInicio && $fechaFeriado <= $fechaFin) {
            $feriadosEnRango++;
            $dtFeriado = new \DateTime($fechaFeriado);
            $diaFeriadoSemana = (int) $dtFeriado->format('N');

            switch ($diaFeriadoSemana) {
                case 1: 
                    if ($totalLunes > 0) $totalLunes--; 
                    if ($diasHabiles > 0) $diasHabiles--; // El feriado en lunes tampoco es hábil laborable
                    break;
                case 2: case 3: case 4: case 5: 
                    if ($diasHabiles > 0) $diasHabiles--; 
                    break;
                case 6: 
                    if ($totalSabados > 0) $totalSabados--; 
                    break;
                case 7: 
                    if ($totalDomingos > 0) $totalDomingos--; 
                    break;
            }
        }
    }

    return [
        'dias_habiles'    => $diasHabiles,          
        'dias_vacaciones' => $diasVacacionesEnRango, 
        'dias_lunes'      => $totalLunes,           
        'dias_sabados'    => $totalSabados,
        'dias_domingos'   => $totalDomingos,
        'dias_feriados'   => $feriadosEnRango,
        'faltas'          => $faltas     
    ];
}

function calcularAsignacionDeduccion($sueldo_base, $sueldo_minimo, $ivss, $faov, $spf, $diasArray = [] ){
        
        $ivssPorcentaje = $ivss / 100;
        $faovPorcentaje = $faov / 100;
        $spfPorcentaje = $spf / 100;

     //Calculos Asignaciones
        $sueldoDiario = ($sueldo_base / 30);
        $asignacionSueldos = $sueldoDiario * $diasArray['dias_habiles']; 
        $asignacionSabados = $sueldoDiario * $diasArray['dias_sabados'];
        $asignacionDomingos = $sueldoDiario * $diasArray['dias_domingos'];
        $asignacionFeriados = $sueldoDiario * $diasArray['dias_feriados'];
        $asignacionTotal = $asignacionDomingos + $asignacionSabados + $asignacionFeriados + $asignacionSueldos;

        // Calculos Deducciones
        $deduccionFaltas = $sueldoDiario * $diasArray['faltas'];

        $deduccionFaov = $asignacionTotal * $faovPorcentaje;

        // IVSS
        $topeSalarioMinimoIvss = $sueldo_minimo * 5;
        if($sueldo_base < $topeSalarioMinimoIvss){
            $topeSalarioMinimoIvss = $sueldo_base; 
        }
        $deduccionIvss = ($topeSalarioMinimoIvss * 12) / 52 * $ivssPorcentaje * $diasArray['dias_lunes'];

        // SPF
        $topeSalarioMinimoSpf = $sueldo_minimo * 10;
        if($sueldo_base < $topeSalarioMinimoSpf){
            $topeSalarioMinimoSpf = $sueldo_base; 
        }
        $deduccionSpf = ($topeSalarioMinimoSpf * 12) / 52 * $spfPorcentaje * $diasArray['dias_lunes'];

        $deduccionTotal = $deduccionFaov + $deduccionIvss + $deduccionSpf + $deduccionFaltas;

        $totalCalculo = $asignacionTotal - $deduccionTotal;

        return [
            'sueldo_diario' => $sueldoDiario,
            'asignacion_sueldo' => $asignacionSueldos,
            'asignacion_sabado' => $asignacionSabados,
            'asignacion_domingo' => $asignacionDomingos,
            'asignacion_feriado' => $asignacionFeriados,
            'asignacion_total' => $asignacionTotal,
            'deduccion_faov' => $deduccionFaov,
            'deduccion_spf' => $deduccionSpf,
            'deduccion_ivss' => $deduccionIvss,
            'deduccion_faltas' => $deduccionFaltas,
            'deduccion_total' => $deduccionTotal,
            'total_calculo' => $totalCalculo
        ];
}

function calcularTicket($monto, $tasa, $fechaInicio, $fechaFin, $arrayFaltas = []){
    $faltas = 0;
 $inicio = new \DateTime($fechaInicio);
    $fin = new \DateTime($fechaFin);
    $fin->modify('+1 day'); // Rango inclusivo

    $intervalo = new \DateInterval('P1D');
    $periodo = new \DatePeriod($inicio, $intervalo, $fin);

    $totalDias = 0; 

    foreach ($periodo as $fecha) {
        $fechaString = $fecha->format('Y-m-d');
        $esFalta = in_array($fechaString, $arrayFaltas);

        if( $esFalta ){
            $faltas++;
        }

        $diaSemana = $fecha->format('N'); // 1 = Lunes, ..., 7 = Domingo

        if ($diaSemana) {
            $totalDias++;
        }

    }

    $montoDiario = $monto / 30;
    $asignacion = ($montoDiario * $totalDias) * $tasa;
    $deduccion = ($montoDiario * $faltas) * $tasa;
    $totalCalculo = $asignacion - $deduccion;
    $diasTrabajados = $totalDias - $faltas;
    return [
        'periodo_dias' => $totalDias,
        'monto_diario' => $montoDiario,
        'asignacion' => $asignacion,
        'deduccion' => $deduccion,
        'faltas' => $faltas,
        'dias_trabajados' => $diasTrabajados,
        'total_calculo' => $totalCalculo
    ];
}

function calcularPeriodoVacaciones($fechaInicio, $diasSolicitados, $arrayFeriados = []) {
    $inicio = new \DateTime($fechaInicio);
    $currentDate = clone $inicio;
    
    $anioActual = (int) $inicio->format('Y');
    $anioAnterior = $anioActual - 1;         
    $periodo = $anioAnterior . '-' . $anioActual; 

    $diasHabilesContados = 0;
    $feriadosEnRango     = 0;
    $sabadosEnRango      = 0;
    $domingosEnRango     = 0;
    $totalDiasCalendario = 0;
    $lunesEnRango        = 0;
    $dias_bonoVacacional = 0;
    $dias_bonoAdicional  = 0;

    if ($diasSolicitados <= 0) {
        return [
            'fecha_fin' => $fechaInicio,
            'dias_habiles' => 0,
            'dias_feriados' => 0,
            'dias_sabados' => 0,
            'dias_domingos' => 0,
            'dias_lunes' => 0,
            'total_dias' => 0,
            'dias_anticipados' => 0,
            'dias_bonoAnticipado' => 0,
            'dias_bonoVacacional' => 0,
            'dias_bonoAdicional' => 0,
            'periodo' => $periodo,
            'fecha_reintegro' => $fechaInicio
        ];
    }

    while ($diasHabilesContados < $diasSolicitados) {
        $fechaString = $currentDate->format('Y-m-d');
        $diaSemana = (int) $currentDate->format('N'); 
        
        $esFeriado = in_array($fechaString, $arrayFeriados);
        $totalDiasCalendario++;

        if ($esFeriado) {
            $feriadosEnRango++;
        } elseif ($diaSemana == 6) {
            $sabadosEnRango++;
        } elseif ($diaSemana == 7) {
            $domingosEnRango++;
        } else {
            $diasHabilesContados++;
        }

        if ($diaSemana == 1) {
            $lunesEnRango++;
        }

        if ($diasHabilesContados < $diasSolicitados) {
            $currentDate->modify('+1 day');
        }
    }

    $fechaFin = $currentDate->format('Y-m-d');

    $fechaReintegroObj = clone $currentDate;
    $fechaReintegroObj->modify('+1 day');

    while (true) {
        $reintegroString = $fechaReintegroObj->format('Y-m-d');
        $diaReintegroSemana = (int) $fechaReintegroObj->format('N');
        $esReintegroFeriado = in_array($reintegroString, $arrayFeriados);

        if ($diaReintegroSemana == 6 || $diaReintegroSemana == 7 || $esReintegroFeriado) {

            $currentDate->modify('+1 day');
            $totalDiasCalendario++;

            $fechaUltimoDiaStr = $currentDate->format('Y-m-d');
            $diaUltimoSemana = (int) $currentDate->format('N');
            $esUltimoFeriado = in_array($fechaUltimoDiaStr, $arrayFeriados);

            if ($esUltimoFeriado) {
                $feriadosEnRango++;
            } elseif ($diaUltimoSemana == 6) {
                $sabadosEnRango++;
            } elseif ($diaUltimoSemana == 7) {
                $domingosEnRango++;
            }

            if ($diaUltimoSemana == 1) {
                $lunesEnRango++;
            }

            $fechaFin = $currentDate->format('Y-m-d');
            $fechaReintegroObj = clone $currentDate;
            $fechaReintegroObj->modify('+1 day');
        } else {
            break;
        }
    }

    $fechaReintegro = $fechaReintegroObj->format('Y-m-d');
    $diasDescanso = $sabadosEnRango + $domingosEnRango;

    return [
        'fecha_fin'           => $fechaFin,
        'dias_habiles'        => $diasHabilesContados,
        'dias_feriados'       => $feriadosEnRango,
        'dias_sabados'        => $sabadosEnRango,
        'dias_domingos'       => $domingosEnRango,
        'dias_descanso'       => $diasDescanso,
        'dias_lunes'          => $lunesEnRango,
        'dias_anticipados'    => 0,
        'dias_bonoAnticipado' => 0,
        'dias_bonoVacacional' => $dias_bonoVacacional,
        'dias_bonoAdicional'  => $dias_bonoAdicional,
        'total_dias'          => $totalDiasCalendario,
        'periodo'             => $periodo,
        'fecha_reintegro'     => $fechaReintegro
    ];
}

function calcularDiasVacacionesPorAntiguedad($fechaIngreso, $diasBase = 15) {
    $fechaIngresoObj = new \DateTime($fechaIngreso);
    
    $hoy = new \DateTime(); //

    $diferencia = $fechaIngresoObj->diff($hoy);
    $anosServicio = $diferencia->y; 
    if($anosServicio >= 1 ) { $anosServicio = $anosServicio - 1; }
    $diasTotales = $diasBase + $anosServicio;
   
    // Opcional: Si tu ley local establece un tope máximo por año (por ejemplo, 30 días), 
    // puedes poner un límite:
    $diasTotales = min($diasTotales, 30);

    return [
        'anos_servicio' => $anosServicio,
        'dias_disponibles' => $diasTotales
    ];
}

function calcularVacaciones($sueldo_mensual, $dias_adicionales, $dias_solicitados, $diasAnticipados, $sueldo_minimo, $faov, $spf, $ivss, $diasArray = []){
    $sueldo_diario = $sueldo_mensual / 30;
    $ivssPorcentaje = $ivss / 100;
    $spfPorcentaje = $spf / 100;
    $faovPorcentaje = $faov / 100;

    // ASIGNACIONES (agregados los operadores de multiplicación *)
    $asignacion_habiles = $sueldo_diario * ($diasArray['dias_habiles'] ?? 0);
    $asignacion_adicionales = $sueldo_diario * $dias_adicionales;
    $asignacion_bonoVacacional = $sueldo_diario * $dias_solicitados;
    $asignacion_diaAdicional = $sueldo_diario * $dias_adicionales;
    $asignacion_feriados = $sueldo_diario * ($diasArray['dias_feriados'] ?? 0);
    $asignacion_descanso = $sueldo_diario * ($diasArray['dias_descanso'] ?? 0);
    
    $asignacion_total = $asignacion_habiles + $asignacion_adicionales + $asignacion_diaAdicional + $asignacion_bonoVacacional + $asignacion_descanso + $asignacion_feriados;

    // DEDUCCIONES (inicializadas en 0)
    $deduccion_anticipados = $sueldo_diario * $diasAnticipados;
    $deduccion_bonoAnticipado = $sueldo_diario * $diasAnticipados;
    $deduccion_islr = 0;
    
    // IVSS
    $topeSalarioMinimoIvss = $sueldo_minimo * 5;
    if($sueldo_mensual < $topeSalarioMinimoIvss){
        $topeSalarioMinimoIvss = $sueldo_mensual; 
    }
    $deduccion_ivss = ($topeSalarioMinimoIvss * 12) / 52 * $ivssPorcentaje * $diasArray['dias_lunes'];

    
    // RPE
    $topeSalarioMinimoSpf = $sueldo_minimo * 10;
    if($sueldo_mensual < $topeSalarioMinimoSpf){
        $topeSalarioMinimoSpf = $sueldo_mensual; 
    }
    $deduccion_rpe = ($topeSalarioMinimoSpf * 12) / 52 * $spfPorcentaje * $diasArray['dias_lunes'];


    $deduccion_rpvh = $asignacion_total * $faovPorcentaje;

    $deduccion_total = $deduccion_anticipados + $deduccion_bonoAnticipado + $deduccion_islr + $deduccion_ivss + $deduccion_rpe + $deduccion_rpvh;

    $asignacion_deduccion_total = $asignacion_total - $deduccion_total;

    return [
        'sueldo_diario' => $sueldo_diario,
        'sueldo_mensual' => $sueldo_mensual,
        'asignacion_habiles' => $asignacion_habiles,
        'asignacion_adicionales' => $asignacion_adicionales,
        'asignacion_bonoVacacional' => $asignacion_bonoVacacional,
        'asignacion_diaAdicional' => $asignacion_diaAdicional,
        'asignacion_feriados' => $asignacion_feriados,
        'asignacion_descanso' => $asignacion_descanso,
        'asignacion_total' => $asignacion_total,
        'deduccion_diasAnticipados' => $deduccion_anticipados,
        'deduccion_bonoAnticipado' => $deduccion_bonoAnticipado,
        'deduccion_islr' => $deduccion_islr,
        'deduccion_ivss' => $deduccion_ivss,
        'deduccion_rpe' => $deduccion_rpe,
        'deduccion_rpvh' => $deduccion_rpvh,
        'deduccion_total' => $deduccion_total,
        'asignacion_deduccion_total' => $asignacion_deduccion_total
    ];
}

