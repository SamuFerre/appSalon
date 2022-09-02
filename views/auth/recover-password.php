<h1 class="nombre-pagina">Recover your password</h1>
<p class="descripcion-pagina">Introduce your new password</p>
<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return; ?>
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input type="password"
                id="password"
                name="password"
                placeholder="New Password"
        />
    </div>
    <input type="submit" class="boton" value="Save New Password"/>
</form>

<div class="acciones">
    <a href="/">You have an account already? Loogin</a>
    <a href="/create-account">Create a new Account</a>
</div>