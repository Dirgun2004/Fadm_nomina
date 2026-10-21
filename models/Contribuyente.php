<?php
namespace Model;

class Contribuyente extends ActiveRecord{
    protected static $tabla = 'tipo_contribuyente';
    protected static $columnasDB = ['id', 'tipo'];
    
    public $id;
    public $tipo;
}