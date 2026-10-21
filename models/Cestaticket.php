<?php
namespace Model;

class Cestaticket extends ActiveRecord{
    protected static $tabla = 'cestaticket';
    protected static $columnasDB = ['id', 'periodo_inicio', 'periodo_fin', 'tasa', 'empresa_id', 'total_asignacion', 'total_deduccion','periodo_dias', 'monto', 'monto_diario', 'total_trabajadores', 'total_faltas', 'total_calculo', 'estatus_id'];
    
    public $id;
    public $periodo_inicio;
    public $periodo_fin;
    public $tasa;
    public $empresa_id;
    public $total_asignacion;
    public $total_deduccion;
    public $periodo_dias;
    public $monto;
    public $monto_diario;
    public $total_trabajadores;
    public $total_faltas;
    public $total_calculo;
    public $estatus_id;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->periodo_inicio = $args['periodo_inicio'] ?? '';
        $this->periodo_fin = $args['periodo_fin'] ?? '';
        $this->tasa = $args['tasa'] ?? 0;
        $this->empresa_id = $args['empresa_id'] ?? '';
        $this->total_asignacion = $args['total_asignacion'] ?? 0;
        $this->total_deduccion = $args['total_deduccion'] ?? 0;
        $this->periodo_dias = $args['periodo_dias'] ?? 0;
        $this->monto = $args['monto'] ?? 0;
        $this->monto_diario = $args['monto_diario'] ?? 0;
        $this->total_trabajadores = $args['total_trabajadores'] ?? 0;
        $this->total_faltas = $args['total_faltas'] ?? 0;
        $this->total_calculo = $args['total_calculo'] ?? 0;
        $this->estatus_id = $args['estatus_id'] ?? 1;
    }

        public function validar() {
        if(!$this->periodo_inicio) {
            self::$alertas['error'][] = 'El periodo de inicio es obligatorio';
        }
        if(!$this->periodo_fin) {
            self::$alertas['error'][] = 'El periodo de fin es obligatorio';
        }
        if(!$this->tasa) {
            self::$alertas['error'][] = 'La Tasa del Dolar es obligatoria';
        }
        if(!$this->monto) {
            self::$alertas['error'][] = 'El monto es obligatorio';
        }
        return self::$alertas;
        }

}