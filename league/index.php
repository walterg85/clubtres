<?php
    session_start();
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<div class="container my-md-4 bd-layout">
    <div class="row justify-content-md-center">
        <div class="col-8">

            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title display-5 fw-bold lh-1 mb-3" id="lblName"></h5>
                            <p class="card-text lead" id="lblInfo"></p>
                            <?php if(isset($_SESSION['login'])) { ?>
                                <div class="mb-3 dvListaEquipo">                               
                                    <div class="d-grid gap-2">
                                        <div class="dropdown">
                                            <a class="btn btn-outline-secondary dropdown-toggle" href="javascript:void(0);" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                Request team admission
                                            </a>

                                            <div class="mb-3 teamItem d-none itemClone">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input chkTeam" name="chkTeam" value="0">
                                                    <label class="form-check-label" for="team1">
                                                        Team 1
                                                    </label>
                                                </div>
                                            </div>

                                            <form class="dropdown-menu p-4 dropdown-menu-end">
                                                <div class="teamContenedor"></div>
                                                <button type="button" class="btn btn-outline-success" id="btnSendRequest">Send request</button>
                                            </form>
                                        </div>                                  
                                    </div>
                                </div>
                            <?php } ?>
                            <p class="card-text"><small class="text-muted labelSubTitle">List of registered teams</small></p>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th class="col1" scope="col">Team name</th>
                                        <th class="col2" scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bdyTeams"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img src="#" class="img-fluid rounded-start" alt="League photo" id="leaguePhoto">
                    </div>
                </div>
            </div>

        </div>
    </div>       
</div>

<script type="text/javascript">
    var leagueId        = "<?php echo $_GET['id']; ?>",
        dataLeague      = null,
        curentLanguage  = null,
        leagueName      = "";

    $(document).ready(function(){
        // Cargar datos de la liga
        fnLoadData();

        //Cargar todos los equipos del usuario logueado
        fnLoadTeam();
    });

    function fnLoadData(){
        if(leagueId){
            let objData = {
                "leagueId": leagueId
            };

            $.get(`${base_url}/core/controllers/league.php`, objData, function(result) {
                if(result.data){
                    $("#lblName").html(`#${result.data.id}`);
                    $("#lblName").append(` ${result.data.name}`);

                    leagueName = result.data.name;

                    if(result.data.image){
                      $("#leaguePhoto").attr("src", `${base_url}/${result.data.image}?v=${Math.random()}`);
                    }else{
                      $("#leaguePhoto").attr("src", `https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/Sport_balls.svg/1200px-Sport_balls.svg.png`);
                    }

                    dataLeague = {
                        "tipo": result.data.sport,
                        "cantidad": (result.data.teamlist).length 
                    }

                    if(result.data.sport == 1){
                      $("#lblInfo").html(`${(result.data.teamlist).length} Soccer league teams`);
                    }else if(result.data.sport == 2){
                      $("#lblInfo").html(`${(result.data.teamlist).length} Basketball league teams`);
                    }else if(result.data.sport == 3){
                      $("#lblInfo").html(`${(result.data.teamlist).length} Football league teams`);
                    }

                    let rows = '';
                    $.each(result.data.teamlist, function( index, item){
                        rows += `
                            <tr>
                                <th scope="row">${pad(item.id, 5)}</th>
                                <td><a href="${base_url}/team/index.php?id=${item.id}" target="_blank" class="btn">${item.name}</a></td>
                                <td class="text-center ${(item.status == 1) ? '' : 'text-danger'}">${(item.status == 1) ? 'Active' : 'Suspended'}</td>
                            </tr>
                        `;
                    });

                    $("#bdyTeams").html("");
                    $(rows).appendTo("#bdyTeams");

                    changePageLang(curentLanguage);

                }else{
                    $("#lblName").html("");
                    $("#leaguePhoto").attr("src", `https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/Sport_balls.svg/1200px-Sport_balls.svg.png`);
                    $("#lblInfo").html("");
                }
            });
        }else{
            window.location.replace(base_url);
        }            
    }

    function changePageLang(language) {
        let myLang = language["leaguePage"];
        curentLanguage = language;

        if(dataLeague){
            $("#lblInfo").html(`${dataLeague.cantidad} ${myLang.title[dataLeague.tipo -1]}`);
            $(".labelSubTitle").html(`${myLang.labelSubTitle}`);

            $(".col1").html(`${myLang.col1}`);
            $(".col2").html(`${myLang.col2}`);
        }        
    }

    // Metodo para listar todos los equipos registrados del usuario logueado
    function fnLoadTeam(){
        let objData = {
            "_method": "getTeamLeague",
            "leagueId": leagueId
        };

        $.post(`${base_url}/core/controllers/team.php`, objData, function(result){
            if(result.data.length > 0){
                $.each(result.data, function(index, item){
                    if(item.type == 1){
                        let team = $(".itemClone").clone();

                        team.find(".chkTeam").attr("id", item.id);
                        team.find(".chkTeam").attr("value", item.id);

                        team.find(".form-check-label")
                            .attr("for", item.id)
                            .html(item.name);

                        team.removeClass("d-none itemClone");
                        $(team).appendTo(".teamContenedor");
                    }
                });

                // Accion para procesar los equipos seleccionados
                $("#btnSendRequest").click( function () {
                    let equiposId = [];

                    $('input[name="chkTeam"]:checked').each(function() {
                       equiposId.push(this.value);
                    });

                    if(equiposId.length == 0)
                        return false;

                    let objData = {
                        "_method": "sendSolicitud",
                        "leagueId": leagueId,
                        "leagueName": leagueName
                    };

                    $.post("../core/controllers/league.php", objData, function(result) {
                        console.log(result);
                    });
                });
            } else {
                $(".dvListaEquipo").addClass("d-none");
            }
        });
    }
</script>

<?php
    // Se obtiene el contenido del bufer
    $content = ob_get_contents();

    // Limpiar el bufer para liberar
    ob_end_clean();

    // Se carga la pagina maestra para imprimir la pagina global
    include("../masterPage.php");
?>