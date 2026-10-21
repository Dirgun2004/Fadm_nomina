<?php
namespace Model;

class Vacaciones extends ActiveRecord{
    protected static $tabla = 'vacaciones';
    protected static $columnasDB = [
        'id', 
        'fecha_inicio', 
        'fecha_fin', 
        'estatus_id', 
        'empleado_id', 
        'dias_solicitados', 
        'dias_feriados', 
        'dias_sabados', 
        'dias_domingos', 
        'dias_lunes', 
        'dias_habiles', 
        'total_dias', 
        'dias_adicionales', 
        'fecha_reintegro', 
        'cargoEmpleado_id', 
        'departamento_id', 
        'sueldo_mensual', 
        'sueldo_diario', 
        'sueldo_mensual_promedio', 
        'sueldo_diario_promedio', 
        'periodo', 
        'dias_disponibles', 
        'dias_restantes',
        'dias_descanso',
        'asignacion_habiles',
        'asignacion_feriados',
        'asignacion_descanso',
        'asignacion_adicionales',
        'asignacion_bonoVacacional',
        'asignacion_diaAdicional',
        'asignacion_total',
        'deduccion_diasAnticipados',
        'deduccion_bonoAnticipado',
        'deduccion_islr',
        'deduccion_ivss',
        'deduccion_rpe',
        'deduccion_rpvh',
        'deduccion_total',
        'asignacion_deduccion_total',
        'empresa_id',
        'dias_anticipados',
        'dias_bonoAnticipado',
        'dias_bonoVacacional',
        'dias_bonoAdicional',
        'sueldo_variable_mensual',
        'sueldo_variable_diario'
    ];
    
    public $id;
    public $fecha_inicio;
    public $fecha_fin;
    public $estatus_id;
    public $empleado_id;
    public $dias_solicitados;
    public $dias_feriados;
    public $dias_sabados;
    public $dias_domingos;
    public $dias_lunes;
    public $dias_habiles;
    public $total_dias;
    public $dias_adicionales;
    public $fecha_reintegro;
    public $cargoEmpleado_id;
    public $departamento_id;
    public $sueldo_mensual;
    public $sueldo_diario;
    public $sueldo_mensual_promedio;
    public $sueldo_diario_promedio;
    public $periodo;
    public $dias_disponibles;
    public $dias_restantes;
    public $dias_descanso;
    public $asignacion_habiles;
    public $asignacion_feriados;
    public $asignacion_descanso;
    public $asignacion_adicionales;
    public $asignacion_bonoVacacional;
    public $asignacion_diaAdicional;
    public $asignacion_total;
    public $deduccion_diasAnticipados;
    public $deduccion_bonoAnticipado;
    public $deduccion_islr;
    public $deduccion_ivss;
    public $deduccion_rpe;
    public $deduccion_rpvh;
    public $deduccion_total;
    public $asignacion_deduccion_total;
    public $empresa_id;
    public $dias_anticipados;
    public $dias_bonoAnticipado;
    public $dias_bonoVacacional;
    public $dias_bonoAdicional;
    public $sueldo_variable_mensual;
    public $sueldo_variable_diario;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->fecha_inicio = $args['fecha_inicio'] ?? '';
        $this->fecha_fin = $args['fecha_fin'] ?? '';
        $this->estatus_id = $args['estatus_id'] ?? 1;
        $this->empleado_id = $args['empleado_id'] ?? '';
        $this->dias_solicitados = $args['dias_solicitados'] ?? null;
        $this->dias_feriados = $args['dias_feriados'] ?? null;
        $this->dias_sabados = $args['dias_sabados'] ?? null;
        $this->dias_domingos = $args['dias_domingos'] ?? null;
        $this->dias_lunes = $args['dias_lunes'] ?? null;
        $this->dias_habiles = $args['dias_habiles'] ?? null;
        $this->total_dias = $args['total_dias'] ?? null;
        $this->dias_adicionales = $args['dias_adicionales'] ?? null;
        $this->fecha_reintegro = $args['fecha_reintegro'] ?? '';
        $this->cargoEmpleado_id = $args['cargoEmpleado_id'] ?? null;
        $this->departamento_id = $args['departamento_id'] ?? null;
        $this->sueldo_mensual = $args['sueldo_mensual'] ?? null;
        $this->sueldo_diario = $args['sueldo_diario'] ?? null;
        $this->sueldo_mensual_promedio = $args['sueldo_mensual_promedio'] ?? null;
        $this->sueldo_diario_promedio = $args['sueldo_diario_promedio'] ?? null;
        $this->periodo = $args['periodo'] ?? '';
        $this->dias_disponibles = $args['dias_disponibles'] ?? null;
        $this->dias_restantes = $args['dias_restantes'] ?? null;
        $this->dias_descanso = $args['dias_descanso'] ?? null;
        $this->asignacion_habiles = $args['asignacion_habiles'] ?? null;
        $this->asignacion_feriados = $args['asignacion_feriados'] ?? null;
        $this->asignacion_descanso = $args['asignacion_descanso'] ?? null;
        $this->asignacion_adicionales = $args['asignacion_adicionales'] ?? null;
        $this->asignacion_bonoVacacional = $args['asignacion_bonoVacacional'] ?? null;
        $this->asignacion_diaAdicional = $args['asignacion_diaAdicional'] ?? null;
        $this->asignacion_total = $args['asignacion_total'] ?? null;
        $this->deduccion_diasAnticipados = $args['deduccion_diasAnticipados'] ?? null;
        $this->deduccion_bonoAnticipado = $args['deduccion_bonoAnticipado'] ?? null;
        $this->deduccion_islr = $args['deduccion_islr'] ?? null;
        $this->deduccion_ivss = $args['deduccion_ivss'] ?? null;
        $this->deduccion_rpe = $args['deduccion_rpe'] ?? null;
        $this->deduccion_rpvh = $args['deduccion_rpvh'] ?? null;
        $this->deduccion_total = $args['deduccion_total'] ?? null;
        $this->asignacion_deduccion_total = $args['asignacion_deduccion_total'] ?? null;
        $this->empresa_id = $args['empresa_id'] ?? null;
        $this->dias_anticipados = $args['dias_anticipados'] ?? null;
        $this->dias_bonoAnticipado = $args['dias_bonoAnticipado'] ?? null;
        $this->dias_bonoVacacional = $args['dias_bonoVacacional'] ?? null;
        $this->dias_bonoAdicional = $args['dias_bonoAdicional'] ?? null;
        $this->sueldo_variable_mensual = $args['sueldo_variable_mensual'] ?? null;
        $this->sueldo_variable_diario = $args['sueldo_variable_mensual'] ?? null;
    }

    public function validar() {
        if(!$this->fecha_inicio) {
            self::$alertas['error'][] = 'La fecha de inicio es obligatoria';
        }
        if(!$this->empleado_id) {
            self::$alertas['error'][] = 'Es obligatorio seleccionar un empleado';
        }

        return self::$alertas;
    }
}