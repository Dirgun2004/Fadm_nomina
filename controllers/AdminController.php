<?php

namespace Controllers;

use Model\Usuarios;
use MVC\Router;

class AdminController {
    public static function registro(Router $router){
        $alertas = [];
        $usuario = new Usuarios;
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = array_map('trim', $_POST);
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuentaNueva();
            if(empty($alertas)){
                $existeUsuario = Usuarios::where('email', $usuario->email);
                if($existeUsuario){
                    Usuarios::setAlerta('error', 'Este correo ya se encuentra registrado');
                    $alertas = Usuarios::getAlertas();
                } else {
                    unset($usuario->password2);
                    $usuario->passwordHash();
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /login-admin');
                    }
                }
            }
        }

        $router->render('admin/registro', [
            'titulo' => 'Registro',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function login(Router $router){
        $alertas = [];
        $usuario = new Usuarios;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarLogin();
            if(empty($alertas)){
                $usuario = Usuarios::where('email', $usuario->email);
                if(!$usuario || $usuario->estatus_id != 1){
                    Usuarios::setAlerta('error', 'El usuario no existe');
                }else{
                    if(password_verify($_POST['password'], $usuario->password)){
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['apellido'] = $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        header('Location: /admin/dashboard');
                    }else{
                        Usuarios::setAlerta('error', 'Contraseña Incorrecta');
                    }
                }

            }
        }
        
        $alertas = Usuarios::getAlertas();
        $router->render('admin/login', [
            'titulo' => 'Login Nomina',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];

        header('Location: /login-admin');
    }

}