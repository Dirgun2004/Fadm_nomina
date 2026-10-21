<?php
namespace Model;

class Nomina extends ActiveRecord{
    protected static $tabla = 'nominas';
    protected static $columnasDB = ['id', 'fecha_inicio', 'fecha_fin', 'empresa_id', 'tipo_nomina', 'tipo_periodo', 'salario_minimo', 'resumen_total', 'resumenTotal_asignacion', 'resumenTotal_deduccion', 'total_empleados', 'resumen_sueldos', 'resumen_sabados', 'resumen_domingos', 'resumen_feriados', 'resumen_faov', 'resumen_ivss', 'resumen_spf', 'porcentaje_faov', 'porcentaje_ivss', 'porcentaje_spf', 'resumen_faltas', 'estatus_id'];

    public $id;
    public $fecha_inicio;
    public $fecha_fin;
    public $empresa_id;
    public $tipo_nomina;
    public $tipo_periodo;
    public $salario_minimo;
    public $resumen_total;
    public $resumenTotal_asignacion;
    public $resumenTotal_deduccion;
    public $total_empleados;
    public $resumen_sueldos;
    public $resumen_sabados;
    public $resumen_domingos;
    public $resumen_feriados;
    public $resumen_faov;
    public $resumen_ivss;
    public $resumen_spf;
    public $porcentaje_faov;
    public $porcentaje_ivss;
    public $porcentaje_spf;
    public $resumen_faltas;
    public $estatus_id;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->fecha_inicio = $args['fecha_inicio'] ?? '';
        $this->fecha_fin = $args['fecha_fin'] ?? '';
        $this->empresa_id = $args['empresa_id'] ?? '';
        $this->tipo_nomina = $args['tipo_nomina'] ?? '';
        $this->tipo_periodo = $args['tipo_periodo'] ?? '';
        $this->salario_minimo = $args['salario_minimo'] ?? '';
        $this->total_empleados = $args['total_empleados'] ?? 0;
        $this->resumenTotal_asignacion = $args['resumenTotal_asignacion'] ?? 0;
        $this->resumenTotal_deduccion = $args['resumenTotal_deduccion'] ?? 0;
        $this->resumen_total = $args['resumen_total'] ?? 0;
        $this->resumen_sueldos = $args['resumen_sueldos'] ?? 0;
        $this->resumen_sabados = $args['resumen_sabados'] ?? 0;
        $this->resumen_domingos = $args['resumen_domingos'] ?? 0;
        $this->resumen_feriados = $args['resumen_feriados'] ?? 0;
        $this->resumen_faov = $args['resumen_faov'] ?? 0;
        $this->resumen_ivss = $args['resumen_ivss'] ?? 0;
        $this->resumen_spf = $args['resumen_spf'] ?? 0;
        $this->porcentaje_faov = $args['porcentaje_faov'] ?? 0;
        $this->porcentaje_ivss = $args['porcentaje_ivss'] ?? 0;
        $this->porcentaje_spf = $args['porcentaje_spf'] ?? 0;
        $this->resumen_faltas = $args['resumen_faltas'] ?? 0;
        $this->estatus_id = $args['estatus_id'] ?? 1;
    }
    
    public function validar() {
        if(!$this->fecha_inicio) {
            self::$alertas['error'][] = 'La Fecha de Inicio es Obligatoria';
        }
        if(!$this->fecha_fin) {
            self::$alertas['error'][] = 'La Fecha de Fin es Obligatoria';
        }
        if(!$this->empresa_id) {
            self::$alertas['error'][] = 'La Empresa es Obligatoria';
        }
        if(!$this->tipo_nomina) {
            self::$alertas['error'][] = 'El Tipo de Nómina es Obligatorio';
        }
        if(!$this->tipo_periodo) {
            self::$alertas['error'][] = 'El Tipo de Período es Obligatorio';
        }
        return self::$alertas;
    }
}