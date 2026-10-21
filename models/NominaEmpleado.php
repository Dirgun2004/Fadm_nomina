<?php
namespace Model;

class NominaEmpleado extends ActiveRecord{
    protected static $tabla = 'nomina_empleado';
    protected static $columnasDB = ['id', 'empleado_id', 'cargo_id', 'sueldo_base', 'dias_habiles', 'dias_lunes', 'dias_sabados', 'dias_domingos', 'dias_feriados', 'dias_vacaciones', 'nomina_id', 'sueldo_diario', 'asignacion_sueldo', 'asignacion_sabado', 'asignacion_domingo', 'asignacion_feriado', 'asignacion_total', 'deduccion_faov', 'deduccion_ivss', 'deduccion_spf', 'deduccion_total', 'total_calculo', 'faltas', 'deduccion_faltas', 'estatus_empleado', 'inicio_vacacion', 'fin_vacacion', 'vacacion_id'];
    
    public $id;
    public $empleado_id;
    public $cargo_id;
    public $sueldo_base;
    public $dias_habiles;
    public $dias_lunes;
    public $dias_sabados;
    public $dias_domingos;
    public $dias_feriados;
    public $dias_vacaciones;
    public $nomina_id;
    public $sueldo_diario;
    public $asignacion_sueldo;
    public $asignacion_sabado;
    public $asignacion_domingo;
    public $asignacion_feriado;
    public $asignacion_total;
    public $deduccion_faov;
    public $deduccion_ivss;
    public $deduccion_spf;
    public $deduccion_total;
    public $total_calculo;
    public $faltas;
    public $deduccion_faltas;
    public $estatus_empleado;
    public $inicio_vacacion;
    public $fin_vacacion;
    public $vacacion_id;


    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->empleado_id = $args['empleado_id'] ?? '';
        $this->cargo_id = $args['cargo_id'] ?? '';
        $this->sueldo_base = $args['sueldo_base'] ?? 0;
        $this->dias_habiles = $args['dias_habiles'] ?? 0;
        $this->dias_lunes = $args['dias_lunes'] ?? 0;
        $this->dias_sabados = $args['dias_sabados'] ?? 0;
        $this->dias_domingos = $args['dias_domingos'] ?? 0;
        $this->dias_feriados = $args['dias_feriados'] ?? 0;
        $this->dias_vacaciones = $args['dias_vacaciones'] ?? 0;
        $this->nomina_id = $args['nomina_id'] ?? '';
        $this->sueldo_diario = $args['sueldo_diario'] ?? 0;
        $this->asignacion_sueldo = $args['asignacion_sueldo'] ?? 0;
        $this->asignacion_sabado = $args['asignacion_sabado'] ?? 0;
        $this->asignacion_domingo = $args['asignacion_domingo'] ?? 0;
        $this->asignacion_feriado = $args['asignacion_feriado'] ?? 0;
        $this->asignacion_total = $args['asignacion_total'] ?? 0;
        $this->deduccion_faov = $args['deduccion_faov'] ?? 0;
        $this->deduccion_ivss = $args['deduccion_ivss'] ?? 0;
        $this->deduccion_spf = $args['deduccion_spf'] ?? 0;
        $this->deduccion_total = $args['deduccion_total'] ?? 0;
        $this->total_calculo = $args['total_calculo'] ?? 0;
        $this->faltas = $args['faltas'] ?? 0;
        $this->deduccion_faltas = $args['deduccion_faltas'] ?? 0;
        $this->estatus_empleado = $args['estatus_empleado'] ?? 0;
        $this->inicio_vacacion = $args['inicio_vacacion'] ?? null;
        $this->fin_vacacion = $args['fin_vacacion'] ?? null;
        $this->vacacion_id = $args['vacacion_id'] ?? null;
    }

    public function validar() {
        if(!$this->empleado_id) {
            self::$alertas['error'][] = 'El Empleado es Obligatorio';
        }
        if(!$this->cargo_id) {
            self::$alertas['error'][] = 'El Cargo es Obligatorio';
        }
        if(!$this->sueldo_base) {
            self::$alertas['error'][] = 'El Sueldo Base es Obligatorio';
        }
        if($this->dias_habiles === '') {
            self::$alertas['error'][] = 'Los Días Hábiles son Obligatorios';
        }
        if($this->lunes_habiles === '') {
            self::$alertas['error'][] = 'Los Lunes Hábiles son Obligatorios';
        }
        if($this->dias_sabados === '') {
            self::$alertas['error'][] = 'Los Días Sábados son Obligatorios';
        }
        if($this->dias_domingos === '') {
            self::$alertas['error'][] = 'Los Días Domingos son Obligatorios';
        }
        if($this->dias_feriados === '') {
            self::$alertas['error'][] = 'Los Días Feriados son Obligatorios';
        }
        if(!$this->nomina_id) {
            self::$alertas['error'][] = 'La Nómina es Obligatoria';
        }
        return self::$alertas;
    }
}