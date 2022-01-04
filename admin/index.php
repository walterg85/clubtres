<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 pageTitle">Home</h1>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4 mb-3 secseccionBody">
    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelUno">Total registered teams</texto>
            </h5>
            <div class="card-body">
                <h3><texto class="labelTres">0</texto></h3>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelUno">Total registered users</texto>
            </h5>
            <div class="card-body">
                <h3><texto class="labelTres">0</texto></h3>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelUno">Total registered leagues</texto>
            </h5>
            <div class="card-body">
                <h3><texto class="labelTres">0</texto></h3>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelUno">Total registered business</texto>
            </h5>
            <div class="card-body">
                <h3><texto class="labelTres">0</texto></h3>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelUno">Total registered games</texto>
            </h5>
            <div class="card-body">
                <h3><texto class="labelTres">0</texto></h3>
            </div>
        </div>
    </div>
</div>

<?php
    // Se obtiene el contenido del bufer
    $content = ob_get_contents();

    // Limpiar el bufer para liberar
    ob_end_clean();

    // Se carga la pagina maestra para imprimir la pagina global
    include("masterPage.php");
?>