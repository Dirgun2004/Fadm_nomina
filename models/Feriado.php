<?php
namespace Model;

class Feriado extends ActiveRecord{
    protected static $tabla = 'feriados';
    protected static $columnasDB = ['id', 'fecha', 'descripcion'];
    
    public $id;
    public $fecha;
    public $descripcion;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
    }

        public function validar() {
        if(!$this->fecha) {
            self::$alertas['error'][] = 'La fecha es obligatoria';
        }
        if(!$this->descripcion) {
            self::$alertas['error'][] = 'La descripcion es obligatoria';
        }
        return self::$alertas;
        }
}