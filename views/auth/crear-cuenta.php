<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/crear-cuenta" class="formulario" method="POST">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Tu Nombre"
            spellcheck="false"
            value="<?php echo s($usuario->nombre); ?>"
        />
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text"
            id="apellido"
            name="apellido"
            placeholder="Tu Apellido"
            spellcheck="false"
            value="<?php echo s($usuario->apellido); ?>"
        />
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input 
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="Tu Teléfono"
            value="<?php echo s($usuario->telefono); ?>"
        />
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tu Email"
            spellcheck="false"
            value="<?php echo s($usuario->email); ?>"
        />
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu Contraseña"
        />
    </div>

    <input type="submit" value="Crear Cuenta" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>