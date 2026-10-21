<?php
namespace Model;

class Sueldo_variable extends ActiveRecord{
    protected static $tabla = 'sueldo_variable';
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

}