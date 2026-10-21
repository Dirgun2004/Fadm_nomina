<?php
namespace Model;

class ParametrosLegales extends ActiveRecord {
    protected static $tabla = 'parametros_legales';
    protected static $columnasDB = ['id', 'nombre_concepto', 'porcentaje', 'fecha_inicio', 'fecha_fin'];

    public $id;
    public $nombre_concepto;
    public $porcentaje;
    public $fecha_inicio;
    public $fecha_fin;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre_concepto = $args['nombre_concepto'] ?? '';
        $this->porcentaje = $args['porcentaje'] ?? '';
        $this->fecha_inicio = $args['fecha_inicio'] ?? '';
        $this->fecha_fin = $args['fecha_fin'] ?? '';
    }
}