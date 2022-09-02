<h1 class="nombre-pagina">Forgot the Password</h1>
<p class="descripcion-pagina">Introduce your email to reset your password</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/forgot">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email"
                id="email"
                name="email"
                placeholder="Your Email"
        />
    </div>
    <input type="submit" value="Reset Password" class="boton"/>
</form>

<div class="acciones">
    <a href="/">Login</a>
    <a href="/create-account">Don't have an account?</a>
</div>