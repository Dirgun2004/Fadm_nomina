<?php
namespace Model;

class NominaPeriodo extends ActiveRecord{
    protected static $tabla = 'nomina_periodo';
    protected static $columnasDB = ['id', 'tipo'];
    
    public $id;
    public $tipo;
}