<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php 
    // include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';    
?>

<form method="POST" class="formulario">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <div class="btns">
        <a href="/public/servicios" class="boton"> < Volver</a>
        <input type="submit" class="boton" value="Actualizar">
    </div>
</form>