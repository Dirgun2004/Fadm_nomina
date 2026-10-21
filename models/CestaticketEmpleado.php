<?php
namespace Model;

class CestaticketEmpleado extends ActiveRecord{
    protected static $tabla = 'cestaticket_empleado';
    protected static $columnasDB = ['id', 'empleado_id', 'cargo_empleado', 'asignacion', 'deduccion', 'cestaticket_id', 'faltas', 'total_calculo', 'dias_trabajados'];
    
    public $id;
    public $empleado_id;
    public $cargo_empleado;
    public $asignacion;
    public $deduccion;
    public $cestaticket_id;
    public $faltas;
    public $total_calculo;
    public $dias_trabajados;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->empleado_id = $args['empleado_id'] ?? '';
        $this->cargo_empleado = $args['cargo_empleado'] ?? '';
        $this->asignacion = $args['asignacion'] ?? 0;
        $this->deduccion = $args['deduccion'] ?? 0;
        $this->cestaticket_id = $args['cestaticket_id'] ?? '';
        $this->faltas = $args['faltas'] ?? 0;
        $this->total_calculo = $args['total_calculo'] ?? 0;
        $this->dias_trabajados = $args['dias_trabajados'] ?? 0;
    }
}