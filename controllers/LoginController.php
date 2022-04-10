<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    //** LOGIN **//
    public static function login(Router $router) {
        $alertas = [];

        $auth = new Usuario();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarlogin();

            if(empty($alertas)) {
                // Comprobrar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    // Verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Este email no está asociado a ninguna cuenta');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
        
    }

    //** LOGOUT **//
    public static function logout() {
        session_start();
        
        $_SESSION = [];

        header('Location: /');
    }

    //** OLVIDE **//
    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){ 
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1") {
                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de Exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'Esta cuenta no existe o no está confirmada');
                   
                }
            }
        }

        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    //** RECUPERAR **//
    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar ususario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                // Seteamos el password en null
                $usuario->pasword = null;
                // Seteamos el password que ingreso el usuario
                $usuario->password = $password->password; 
                $usuario->hashPassword($password);
                // Seteamos el token a null
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    //** CREAR **//
    public static function crear(Router $router) {

        $usuario = new Usuario;

        // Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que las alertas estén vacias
            if(empty($alertas)) {
                // Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // No está registrado
                    $usuario->hashPassword();

                    // Generar un token único
                    $usuario->crearToken();

                    // Enviar el Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }

        }

        // Renderizar la vista
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    //** MENSAJE **//
    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');   
    }

    //** CONFIRMAR **//
    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no Válido');
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobrado Correctamente');
        }

        // Obtener Alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);   
    }
}