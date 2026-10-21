<?php
namespace Model;

class Sueldo_minimo extends ActiveRecord{
    protected static $tabla = 'sueldo_minimo';
    protected static $columnasDB = ['id', 'sueldo'];
    
    public $id;
    public $sueldo;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->sueldo = $args['sueldo'] ?? '';
    }

        public function validar() {
        if(!$this->sueldo) {
            self::$alertas['error'][] = 'El sueldo basico es obligatorio';
        }
        return self::$alertas;
        }
}