<?php
namespace Model;

class Empleado extends ActiveRecord{
    protected static $tabla = 'empleados';
    protected static $columnasDB = ['id', 'nombres', 'apellidos', 'documento_id', 'cedula', 'rif', 'ingreso', 'egreso', 'cargo_id', 'empresa_id', 'estatus_id', 'departamento_id'];
    
    public $id;
    public $nombres;
    public $apellidos;
    public $documento_id;
    public $cedula;
    public $rif;
    public $ingreso; 
    public $egreso; 
    public $cargo_id; 
    public $empresa_id; 
    public $estatus_id;
    public $departamento_id;


    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombres = $args['nombres'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->documento_id = $args['documento_id'] ?? '';
        $this->cedula = $args['cedula'] ?? '';
        $this->rif = $args['rif'] ?? '';
        $this->ingreso = $args['ingreso'] ?? '';
        $this->egreso = $args['egreso'] ?? null;
        $this->cargo_id = $args['cargo_id'] ?? '';
        $this->empresa_id = $args['empresa_id'] ?? '';
        $this->estatus_id = $args['estatus_id'] ?? 1;
        $this->departamento_id = $args['departamento_id'] ?? null;
    }

    public function validar() {
        if(!$this->nombres) {
            self::$alertas['error'][] = 'Los nombres son obligatorios';
        }
        if(!$this->apellidos) {
            self::$alertas['error'][] = 'Los apellidos son obligatorios';
        }
        if(!$this->cedula) {
            self::$alertas['error'][] = 'La cedula es obligatoria';
        }
        if(!$this->rif) {
            self::$alertas['error'][] = 'El rif es Obligatorio';
        }
        if(!$this->ingreso) {
            self::$alertas['error'][] = 'La fecha de ingreso es Obligatoria';
        }
        if(!$this->cargo_id) {
            self::$alertas['error'][] = 'El cargo en la empresa es Obligatorio';
        }
        if(!$this->departamento_id) {
            self::$alertas['error'][] = 'El Departamento en la empresa es Obligatorio';
        }
        return self::$alertas;
    }
}