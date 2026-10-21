<?php
namespace Model;

class FaltasEmpleados extends ActiveRecord{
    protected static $tabla = 'faltas_empleado';
    protected static $columnasDB = ['id', 'fecha', 'descripcion', 'empleado_id'];
    
    public $id;
    public $fecha;
    public $descripcion;
    public $empleado_id;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->empleado_id = $args['empleado_id'] ?? '';
    }

        public function validar() {
        if(!$this->fecha) {
            self::$alertas['error'][] = 'La fecha es obligatoria';
        }
        return self::$alertas;
        }
}