<h1 class="nombre-pagina">Make a reservation</h1>
<p class="descripcion-pagina">Choose the the services, date and time</p>

<div class="app">
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Services</button>
        <button type="button" data-paso="2">Date and Time</button>
        <button type="button" data-paso="3">Information</button>
    </nav>
    <div class="seccion" id="paso-1">
        <h2>Services</h2>
        <p class="text-center">Choose your services below</p>
        <div class="listado-servicios" id="servicios"></div>

    </div>
    <div class="seccion" id="paso-2">
        <h2>Reservation Date</h2>
        <p class="text-center">Choose a Day and a Time</p>
        <form class="formulario">
            <div class="campo">
                <label for="name">Name</label>
                <input type="text"
                        id="name"
                        placeholder="Your Name"
                        value="<?php echo $name; ?>"
                        disabled/>
            </div>
            <div class="campo">
                <label for="date">Date</label>
                <input type="date"
                        id="date"
                        min="<?php echo Date('Y-m-d', strtotime('+1 day') ); ?>"
                        />
            </div>
            <div class="campo">
                <label for="time">Time</label>
                <input type="time"
                        id="time"
                        />
            </div>
        </form>
    </div>
    <div class="seccion contenido-resumen" id="paso-3">
        <h2>Summary</h2>
        <p class="text-center">Verify if the information is correct</p>
    </div>

    <div class="paginacion">
        <button class="boton" id="anterior" >&laquo; Previous</button>
   
        <button class="boton" id="siguiente" >Next &raquo;</button>
    </div>
</div>

<?php $script = 
    "
        <script src='build/js/app.js'></script>
    "; 
?>