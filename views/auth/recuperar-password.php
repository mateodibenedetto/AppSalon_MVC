<h1 class="nombre-pagina">Recuperar Contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuación</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return; ?>

<form class="formulario" method="POST">

    <div class="campo">
        <label for="password">Contraseña</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu nueva contraseña"
        >
    </div>

    <input type="submit" class="boton" value="Actualizar contraseña">

</form>

<div class="acciones">
    <a href="/public/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/public/crear-cuenta">¿Aún no tienes una cuenta? Crea una cuenta</a>
</div>