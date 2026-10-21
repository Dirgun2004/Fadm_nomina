<?php
namespace Model;

class Empresa extends ActiveRecord{
    protected static $tabla = 'empresas';
    protected static $columnasDB = ['id', 'nombre', 'rif', 'documento_id', 'direccion', 'estatus_id', 'contribuyente_id', 'impuesto_id'];
    
    public $id;
    public $nombre;
    public $rif;
    public $direccion;
    public $estatus_id;
    public $contribuyente_id;
    public $documento_id;
    public $impuesto_id;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->documento_id = $args['documento_id'] ?? '';
        $this->rif = $args['rif'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->estatus_id = $args['estatus_id'] ?? 1;
        $this->contribuyente_id = $args['contribuyente_id'] ?? '';
        $this->impuesto_id = $args['impuesto_id'] ?? null;
    }

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->rif) {
            self::$alertas['error'][] = 'El rif es Obligatorio';
        }
        if(!$this->direccion) {
            self::$alertas['error'][] = 'La direccion es Obligatoria';
        }
        if(!$this->estatus_id) {
            self::$alertas['error'][] = 'El estado de la empresa es Obligatorio';
        }
        if(!$this->contribuyente_id) {
            self::$alertas['error'][] = 'El tipo de contribuyente es Obligatorio';
        }
        return self::$alertas;
    }
}