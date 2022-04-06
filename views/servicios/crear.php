<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">Llena todos los campos para agregar un nuevo servicio</p>

<?php 
    // include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';    
?>

<form action="/public/servicios/crear" method="POST" class="formulario">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <div class="btns">
        <a href="/public/servicios" class="boton"> < Volver</a>
        <input type="submit" class="boton" value="Guadar Servicio">
    </div>
</form>