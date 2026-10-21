<?php
namespace Model;

class TipoDocumento extends ActiveRecord{
    protected static $tabla = 'tipo_documento';
    protected static $columnasDB = ['id', 'tipo'];
    
    public $id;
    public $tipo;
}