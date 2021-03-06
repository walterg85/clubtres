<?php
    session_start();
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<div class="container my-md-4 bd-layout">
    <div class="row justify-content-md-center">
        <div class="col-8">
            <div class="card mb-3">                    
                <img src="#" class="card-img-top" alt="Team photo" id="teamPhoto">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <h4 class="card-title" id="lblNombreTeam"></h4>
                            <p class="card-text" id="numMember"></p>
                            <p class="card-text"><small class="text-muted labelSubtitle">Members list</small></p>
                        </div>
                        <?php if(isset($_SESSION['login'])) { ?>
                            <div class="col">
                                <div class="d-grid gap-2">
                                  <button class="btn btn-outline-success" id="btnAdmission" type="button">Admission request</button>
                                  <p class="lead d-none btnEnviado"><i class="bi bi-send-check"></i> Admission request sent</p>
                                  <p class="lead d-none btnMiembro"><i class="bi bi-calendar-event"></i> <text class="lblMember"> Member since</text> <text class="lblDate"></text> </p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th class="col1" scope="col">Full name</th>
                                <th class="col2" scope="col">Type</th>
                                <th class="col3" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="bdyUser"></tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>       
</div>

<script type="text/javascript">
    var teamId          = "<?php echo $_GET['id']; ?>",
        curentLanguage  = null,
        countMember     = 0,
        teamName        = "";

    $(document).ready(function(){
        // Cargar datos del team
        fnLoadData();

        // Revisar el estado de las solicitudes
        reviewRequest();

        // Envio de solicitud
        $("#btnAdmission").click( sendSolicitud);
    });

    function fnLoadData(){
        if(teamId){
            let objData = {
                "teamId": teamId
            };

            $.get(`${base_url}/core/controllers/team.php`, objData, function(result) {
                if(result.data){
                    $("#lblNombreTeam").html(`#${result.data.id}`);
                    $("#lblNombreTeam").append(` ${result.data.name}`);

                    teamName    = result.data.name;
                    srcImg      = (result.data.image) ? `${base_url}/${result.data.image}?v=${Math.random()}` : `https://i.imgur.com/KIKKMcK.jpg`;

                    $("#teamPhoto").attr("src", srcImg);
                    $("#numMember").html(`${(result.data.teamlist).length} Members`);
                    countMember = (result.data.teamlist).length;

                    let rows = '';
                    $.each(result.data.teamlist, function( index, item){
                        rows += `
                            <tr>
                                <th scope="row">${pad(item.id, 5)}</th>
                                <td><a href="${base_url}/user/index.php?id=${item.id}" target="_blank" class="btn">${item.usName}</a></td>
                                <td>${item.role}</td>
                                <td class="text-center ${(item.status == 1) ? '' : 'text-danger'}">${(item.status == 1) ? 'Active' : 'Suspended'}</td>
                            </tr>
                        `;
                    });

                    $("#bdyUser").html(rows);
                    changePageLang(curentLanguage);

                }else{
                    $("#lblNombreTeam").html("");
                    $("#teamPhoto").attr("src", `https://i.imgur.com/KIKKMcK.jpg`);
                }
            });
        }else{
            window.location.replace(base_url);
        }            
    }

    function changePageLang(language) {
        let myLang      = language["teamPage"];
        curentLanguage  = language;

        if(countMember > 0){
            $("#numMember").html(`${countMember} ${myLang.title}`);
            $(".labelSubtitle").html(`${myLang.labelSubtitle}`);

            $(".col1").html(`${myLang.col1}`);
            $(".col2").html(`${myLang.col2}`);
            $(".col3").html(`${myLang.col3}`);
        }        
    }

    // Metodo para revisar el estado de solicitudes y valorar el elemneto a mostrar
    function reviewRequest(){
        let objData = {
            "_method": "reviewRequest",
            "teamId": teamId
        };

        $.post("../core/controllers/team.php", objData, function(result) {
            let intStatus = result.status[0];

            if(intStatus == 0){
                $("#btnAdmission").removeClass("d-none");
            }else{
                $("#btnAdmission").addClass("d-none");

                if(intStatus == 1){
                    $(".btnMiembro").removeClass("d-none");
                    $(".lblDate").html(result.status[1]);
                } else if(intStatus == 2){
                    $(".btnEnviado").removeClass("d-none");
                }
            }
        });
    }

    // MEtodo para el envio de solicitud de admision
    function sendSolicitud(){
        let objData = {
            "_method": "sendSolicitud",
            "teamId": teamId,
            "teamName": teamName
        };

        $.post("../core/controllers/team.php", objData, function(result) {
            reviewRequest();
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