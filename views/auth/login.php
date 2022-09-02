<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Introduce your username and password</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            placeholder="Your Email"
            name="email"
        />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            placeholder="Your Password"
            name="password"
        />
    </div>
    <input type="submit" class="boton" value="Login">
</form>

<div class="acciones">
    <a href="/create-account">Don't have an account?</a>
    <a href="/forgot">Have you forgot your password?</a>
</div>