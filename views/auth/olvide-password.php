<h1 class="nombre-pagina">Olvide Contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu email a continuación</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/public/olvide" class="formulario" method="POST">

    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tu Email"
            spellcheck="false"
            required
        >
    </div>

    <input type="submit" class="boton" value="Enviar Instrucciones">

</form>

<div class="acciones">
    <a href="/public/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/public/crear-cuenta">¿Aún no tienes una cuenta? Crea una cuenta</a>
</div>