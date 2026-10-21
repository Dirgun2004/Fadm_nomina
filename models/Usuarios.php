<?php
namespace Model;

class Usuarios extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'estatus_id', 'rol', 'token'];
    
    public $id;
    public $nombre;
    public $apellido;
    public $password;
    public $password2;
    public $estatus_id;
    public $rol;
    public $email;
    public $token;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->estatus_id = $args['estatus_id'] ?? 1;
        $this->rol = $args['rol'] ?? 1;
        $this->token = $args['token'] ?? null;
    }

    public function validarCuentaNueva() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es Obligatoria';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener minimo 8 caracteres';
        }
        if($this->password !== $this->password2){
            self::$alertas['error'][] = 'Las contraseñas tienen que coincidir';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El formato del correo no es válido';
        }

        return self::$alertas;
    }
    public function validarLogin() {
        if(!$this->password){
            self::$alertas['error'][] = 'Las contraseñas es obligatoria';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }

        return self::$alertas;
    }

    public function passwordHash(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
}