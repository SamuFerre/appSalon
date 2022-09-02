<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Fill out the next form and create an account</p>


<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/create-account" class="formulario" method="POST">
    <div class="campo">
        <label for="name">First Name</label>
        <input 
            type="text"
            id="name"
            name="name"
            placeholder="Your First name"
            value="<?php echo s($user->name); ?>"
        />
    </div>
    <div class="campo">
        <label for="surname">Last Name</label>
        <input 
            type="text"
            id="surname"
            name="surname"
            placeholder="Your Last Name"
            value="<?php echo s($user->surname); ?>"
        />
    </div>
    <div class="campo">
        <label for="phone">Phone Number</label>
        <input 
            type="tel"
            id="phone"
            name="phone"
            placeholder="Your Phone Number"
            value="<?php echo s($user->phone); ?>"
        />
    </div>
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Your Email"
            value="<?php echo s($user->email); ?>"
        />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Your Password"
        />
    </div>

    <input type="submit" value="Create Account" class="boton"/>
</form>

<div class="acciones">
    <a href="/">You already have an account?</a>
    <a href="/forgot">Have you forgot your password?</a>
</div>