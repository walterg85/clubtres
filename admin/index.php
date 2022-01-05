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
                <texto class="labelGroupTeam">Teams registered</texto>
            </h5>
            <div class="card-body">
                <h6><texto class="labelTeamTotal">Total registered:</texto> <texto id="teamTotal"></texto></h6>
                <h6><texto class="labelTeamActivo">Total active:</texto> <texto id="teamActive"></texto></h6>
                <h6><texto class="labelTeamInactivo">Total inactive:</texto> <texto id="teamInactive"></texto></h6>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelGroupUser">Users registered</texto>
            </h5>
            <div class="card-body">
                <h6><texto class="labelUserTotal">Total registered:</texto> <texto id="usersTotal"></texto></h6>
                <h6><texto class="labelUserActivo">Total active:</texto> <texto id="usersActive"></texto></h6>
                <h6><texto class="labelUserInactivo">Total inactive:</texto> <texto id="usersInactive"></texto></h6>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelGroupLeague">Leagues registered</texto>
            </h5>
            <div class="card-body">
                <h6><texto class="labelLeagueTotal">Total registered:</texto> <texto id="leaguesTotal"></texto></h6>
                <h6><texto class="labelLeagueActivo">Total active:</texto> <texto id="leaguesActive"></texto></h6>
                <h6><texto class="labelLeagueInactivo">Total inactive:</texto> <texto id="leaguesInactive"></texto></h6>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelGroupBusiness">Business registered</texto>
            </h5>
            <div class="card-body">
                <h6><texto class="labelBusinessTotal">Total registered:</texto> <texto id="businessTotal"></texto></h6>
                <h6><texto class="labelBusinessActivo">Total active:</texto> <texto id="businessActive"></texto></h6>
                <h6><texto class="labelBusinessInactico">Total inactive:</texto> <texto id="businessInactive"></texto></h6>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">
                <texto class="labelGroupGames">Games registered</texto>
            </h5>
            <div class="card-body">
                <h6><texto class="labelGameTotal">Total registered:</texto> <texto id="gamesTotal"></texto></h6>
                <h6><texto class="labelGameActivo">Total pending:</texto> <texto id="gamesActive"></texto></h6>
                <h6><texto class="labelGameInactivo">Total finalized:</texto> <texto id="gamesInactive"></texto></h6>
            </div>
        </div>
    </div>
</div>

<div class="row secseccionBody">
    <div class="col-6">
        <p class="lead mb-0 chartA labelTitutloGrafico">Registered users per month in the current year</p>
        <canvas class="my-4 w-100" id="myChartA"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>

<script type="text/javascript">
    var arrMes = {
            "en": ["January","February","March","April","May","June","July", "August","September","October","November","December"],
            "es": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
        },
        datosGrafica = null;

    $(document).ready(function(){
        // Buscar el detalle de resumen
        fnGetReport();
    });

    function fnGetReport(){
        let _Data = {
            "_method": "GET"
        };

        $.post("../core/controllers/admin.php", _Data, function(result){
            let dato = result.data;

            $("#teamTotal").html(dato.total_team);
            $("#teamActive").html(dato.active_team);
            $("#teamInactive").html(dato.inactive_team);

            $("#usersTotal").html(dato.total_user);
            $("#usersActive").html(dato.active_user);
            $("#usersInactive").html(dato.inactive_user);

            $("#leaguesTotal").html(dato.total_league);
            $("#leaguesActive").html(dato.active_league);
            $("#leaguesInactive").html(dato.inactive_league);

            $("#businessTotal").html(dato.total_business);
            $("#businessActive").html(dato.active_business);
            $("#businessInactive").html(dato.inactive_business);

            $("#gamesTotal").html(dato.total_games);
            $("#gamesActive").html(dato.pending_games);
            $("#gamesInactive").html(dato.passed_games);

            datosGrafica = result.chart1;

            generateChart();
        });
    }

    function generateChart(){
        let canvaA   = document.getElementById('myChartA'),
            myChartA = new Chart(canvaA, {
                type: 'line',
                data: {
                    labels: arrMes[lang],
                    datasets: [{
                        data: datosGrafica,
                        lineTension: 0,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        borderWidth: 4,
                        pointBackgroundColor: '#007bff'
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
            });
    }

    function changePageLang(myLang){
        $(".pageTitle").html(myLang.pageTitle);

        $(".pageTitle").html(myLang.pageTitle);
        $(".labelGroupTeam").html(myLang.labelGroupTeam);
        $(".labelGroupUser").html(myLang.labelGroupUser);
        $(".labelGroupLeague").html(myLang.labelGroupLeague);
        $(".labelGroupBusiness").html(myLang.labelGroupBusiness);
        $(".labelGroupGames").html(myLang.labelGroupGames);
        $(".labelTeamTotal").html(myLang.labelTeamTotal);
        $(".labelTeamActivo").html(myLang.labelTeamActivo);
        $(".labelTeamInactivo").html(myLang.labelTeamInactivo);
        $(".labelUserTotal").html(myLang.labelUserTotal);
        $(".labelUserActivo").html(myLang.labelUserActivo);
        $(".labelUserInactivo").html(myLang.labelUserInactivo);
        $(".labelLeagueTotal").html(myLang.labelLeagueTotal);
        $(".labelLeagueActivo").html(myLang.labelLeagueActivo);
        $(".labelLeagueInactivo").html(myLang.labelLeagueInactivo);
        $(".labelBusinessTotal").html(myLang.labelBusinessTotal);
        $(".labelBusinessActivo").html(myLang.labelBusinessActivo);
        $(".labelBusinessInactico").html(myLang.labelBusinessInactico);
        $(".labelGameTotal").html(myLang.labelGameTotal);
        $(".labelGameInactivo").html(myLang.labelGameInactivo);
        $(".labelTitutloGrafico").html(myLang.labelTitutloGrafico);

        generateChart();
    }
</script>

<?php
    // Se obtiene el contenido del bufer
    $content = ob_get_contents();

    // Limpiar el bufer para liberar
    ob_end_clean();

    // Se carga la pagina maestra para imprimir la pagina global
    include("masterPage.php");
?>