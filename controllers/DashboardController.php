<?php

namespace Controllers;

use MVC\Router;

class DashboardController{
    public static function index(Router $router){
        
        $router->render('admin/index', [
            'titulo' => 'Panel de Control'
        ]);
    }
    public static function nomina(Router $router){
        
        $router->render('admin/nomina', [
            'titulo' => 'Nominas'
        ]);
    }
    public static function cestaticket(Router $router){
        
        $router->render('admin/cestaticket', [
            'titulo' => 'Nominas'
        ]);
    }
    public static function bonos(Router $router){
        
        $router->render('admin/bonos', [
            'titulo' => 'Nominas'
        ]);
    }

}