<?php
namespace Model;

class Cargo extends ActiveRecord{
    protected static $tabla = 'cargos';
    protected static $columnasDB = ['id', 'nombre'];
    
    public $id;
    public $nombre;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

        public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        return self::$alertas;
        }
}