<?php

namespace Controllers;

error_reporting(E_ALL ^ E_NOTICE); // Para que no muestre los notice

use MVC\Router;

class CitaController {
    public static function index( Router $router ) {

        session_start();

        isAuth();

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }
}