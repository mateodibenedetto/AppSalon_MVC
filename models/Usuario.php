<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio'; 
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio'; 
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio'; 
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es Obligatoria'; 
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        return self::$alertas;
    }

    // Mensajes de validación para el login
    public function validarLogin() {
        if(!$this->email) self::$alertas['error'][] = 'El Email es Obligatorio'; 

        if(!$this->password) self::$alertas['error'][] = 'La contraseña es Obligatoria'; 
        
        return self::$alertas;
    }

    // Validación para el email
    public function validarEmail() {
        if(!$this->email) self::$alertas['error'][] = 'El Email es Obligatorio'; 
        
        return self::$alertas;
    }

    // Validar password
    public function validarPassword() {
        if(!$this->password) self::$alertas['error'][] = 'La contraseña es Obligatoria'; 

        if(strlen($this->password) < 5) self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        
        return self::$alertas;
    }

    // Revisa si el usuario y existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) self::$alertas['error'][] = 'Ya hay una cuenta resgistrada con este email';

        return $resultado;
    }

    // Hashea la contraseña
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Genera un id único para el token
    public function crearToken() {
        $this->token = uniqid(); // uniqid() gener un id unico
    }

    // Comprobar que Contraseña y que esté verificado
    public function comprobarPasswordAndVerificado($password) {

        $resultado = password_verify($password, $this->password);
        
        if(!$resultado) {
            self::$alertas['error'][] = 'Contraseña incorrecta';
        // } else if (!$this->confirmado) {
        //     self::$alertas['error'][] = 'Tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }
}
