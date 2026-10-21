<?php
namespace Model;

class Estatus extends ActiveRecord{
    protected static $tabla = 'estatus';
    protected static $columnasDB = ['id', 'estado'];
    
    public $id;
    public $estado;
}