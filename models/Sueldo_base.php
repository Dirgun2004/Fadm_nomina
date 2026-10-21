<?php
namespace Model;

class Sueldo_base extends ActiveRecord{
    protected static $tabla = 'sueldo_base';
    protected static $columnasDB = ['id', 'sueldo', 'empleado_id', 'fecha'];
    
    public $id;
    public $sueldo;
    public $empleado_id;
    public $fecha;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->sueldo = $args['sueldo'] ?? '';
        $this->empleado_id = $args['empleado_id'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
    }

        public function validar() {
        if(!$this->sueldo) {
            self::$alertas['error'][] = 'El sueldo basico es obligatorio';
        }
        return self::$alertas;
        }
}