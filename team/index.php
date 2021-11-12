<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<div class="container my-md-4 bd-layout">
    <div class="row justify-content-md-center">
        <div class="col-8">
            <div class="card mb-3">                    
                <img src="#" class="card-img-top" alt="Team photo" id="teamPhoto">
                <div class="card-body">
                    <h4 class="card-title" id="lblNombreTeam"></h4>
                    <p class="card-text" id="numMember"></p>
                    <p class="card-text"><small class="text-muted">Members list</small></p>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Full name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Status</th>
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
    var teamId = "<?php echo $_GET['id']; ?>";

    $(document).ready(function(){
        // Cargar datos del team
        fnLoadData();
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

                    if(result.data.image){
                      $("#teamPhoto").attr("src", `${base_url}/${result.data.image}?v=${Math.random()}`);
                    }else{
                      $("#teamPhoto").attr("src", `#`);
                    }

                    $("#numMember").html(`${(result.data.teamlist).length} Members`);

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

                    $("#bdyUser").html("");
                    $(rows).appendTo("#bdyUser");

                }else{
                    $("#lblNombreTeam").html("");
                    $("#teamPhoto").attr("src", `#`);
                }
            });
        }else{
            window.location.replace(base_url);
        }            
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