<?php

namespace Model;

class CitaServicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'citasServicios';
    protected static $columnasDB = ['id', 'turnoId', 'servicioId'];

    public $id;
    public $turnoId;
    public $servicioId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->turnoId = $args['turnoId'] ?? '';
        $this->servicioId = $args['servicioId'] ?? '';
    }
}