<?php
namespace Model;

class BonoEmpleado extends ActiveRecord{
    protected static $tabla = 'bono_empleado';
    protected static $columnasDB = ['id', 'concepto', 'empleado_id', 'monto_usd', 'monto_bs', 'tasa', 'fecha', 'mes_year', 'tipo_periodo', 'estatus_id', 'empresa_id'];
    
    public $id;
    public $concepto;
    public $empleado_id;
    public $monto_usd;
    public $monto_bs;
    public $tasa;
    public $fecha;
    public $mes_year;
    public $tipo_periodo;
    public $periodo;
    public $total_registros;
    public $monto_total_usd;
    public $estatus_id;
    public $empresa_id;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->concepto = $args['concepto'] ?? '';
        $this->empleado_id = $args['empleado_id'] ?? '';
        $this->monto_usd = $args['monto_usd'] ?? 0;
        $this->monto_bs = $args['monto_bs'] ?? 0;
        $this->tasa = $args['tasa'] ?? 0;
        $this->fecha = $args['fecha'] ?? '';
        $this->mes_year = $args['mes_year'] ?? '';
        $this->tipo_periodo = $args['tipo_periodo'] ?? '';
        $this->estatus_id = $args['estatus_id'] ?? 1;
        $this->empresa_id = $args['empresa_id'] ?? '';
    }

    public function validar() {
        if(!$this->monto_usd) {
            self::$alertas['error'][] = 'El monto en USD es obligatorio';
        }
        if(!$this->tasa) {
            self::$alertas['error'][] = 'La tasa de cambio es obligatoria';
        }
        if(!$this->concepto) {
            self::$alertas['error'][] = 'El concepto del bono es obligatorio';
        }
        if(!$this->fecha) {
            self::$alertas['error'][] = 'La fecha de pago del bono es obligatoria';
        }
        if(!$this->tipo_periodo) {
            self::$alertas['error'][] = 'El tipo de periodo es obligatorio';
        }

        return self::$alertas;
    }

    // 1. Contar el total de meses únicos pero filtrados por empresa
    public static function totalMesesUnicosPorEmpresa($id_empresa) {
        $query = "SELECT COUNT(DISTINCT mes_year) 
                  FROM " . static::$tabla . " 
                  WHERE empresa_id = {$id_empresa} AND estatus_id = '1'";
                  
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();
        return array_shift($total);
    }    

    // 2. Obtener los meses paginados y agrupados de esa empresa
    public static function paginarMesesPorEmpresa($id_empresa, $por_pagina, $offset) {

        $query = "SELECT 
            mes_year as periodo, 
            COUNT(*) as total_registros,
            SUM(monto_usd) as monto_total_usd
            FROM " . static::$tabla . "
            WHERE empresa_id = {$id_empresa}
            AND estatus_id = '1'
            GROUP BY mes_year 
            ORDER BY mes_year DESC 
            LIMIT {$por_pagina} OFFSET {$offset}";
                  
        return self::consultarSQL($query);
    }

    public static function totalRegistrosPorEmpresaYPeriodo($id_empresa, $periodo) {
        $query = "SELECT COUNT(*)
                  FROM " . static::$tabla . 
                 " WHERE empresa_id = {$id_empresa} AND estatus_id = '1' AND mes_year = '{$periodo}'";
                  
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();
        return array_shift($total);
    }
}
