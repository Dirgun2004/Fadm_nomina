<?php
namespace Model;

class TipoNomina extends ActiveRecord{
    protected static $tabla = 'tipo_nomina';
    protected static $columnasDB = ['id', 'tipo'];
    
    public $id;
    public $tipo;
}