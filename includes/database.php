<?php

// Variables de Entorno

$db = mysqli_connect(
    // Local Host
    // $_ENV['DB_HOST'],
    // $_ENV['DB_USER'],
    // $_ENV['DB_PASS'], // ?? '' 
    // $_ENV['DB_BD']

    $_ENV['DB_HOST_P'],
    $_ENV['DB_USER_P'],
    $_ENV['DB_PASS_P'],
    $_ENV['DB_BD_P']
);


if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
} 
