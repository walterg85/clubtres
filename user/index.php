<?php
    session_start();
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<div class="container">
    <div class="container col-xxl-8 px-4 py-5">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-10 col-sm-8 col-lg-6">
                <img src="../assets/img/user/default.jpg" class="d-block mx-lg-auto img-fluid shadow" id="userPhoto" alt="User picture" width="700" height="500" loading="lazy">
            </div>
            <div class="col-lg-6">
                <h1 class="fw-bold lh-1 mb-0" id="userName"></h1>
                <p class="h1 mt-0" id="userId"></p>
                <p class="lead" id="userStatus"></p>
                <?php if(isset($_SESSION['login'])) { ?>
                    <a href="javascript:void(0);" class="btn btn-success d-none" id="btnEnviarSolicitud"><i class="bi bi-person-plus-fill"></i> Solicitud de amistad</a>
                    <p class="lead d-none btnEnviado"><i class="bi bi-person-plus-fill"></i> Friend request sent</p>
                    <p class="lead d-none btnRecibido"><i class="bi bi-person-plus-fill"></i> Friend request received</p>
                    <p class="lead d-none btnFriends"><i class="bi bi-person-check-fill"></i> Friends since <text class="lblDate"></text> </p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var userId = "<?php echo $_GET['id']; ?>";

    $(document).ready(function(){
        // Cargar datos del usuario
        fnLoadData();

        // Verificar las solicitudes de amistad
        reviewFriendRequest();

        $("#btnEnviarSolicitud").click( sendInvitation);
    });

    function fnLoadData(){
        if(userId){
            let objData = {
                "_method": "GET",
                "userId": userId
            };

            $.post(`${base_url}/core/controllers/user.php`, objData, function(result) {
                if(result.data){
                    $("#userName").html(`${result.data.name} ${result.data.last_name}`);
                    $("#userId").html(`#${result.data.id}`);
                    $("#userStatus").html( (result.data.active == 1) ? "Active" : "Disbaled" );                        
                    $("#userPhoto").attr("src", `${base_url}/${result.data.image}?v=${Math.random()}`);
                }else{
                    $("#userName").html("");
                    $("#userId").html("");
                    $("#userStatus").html("");
                }
            });
        }else{
            // Si no se carga correctamente el objeto se redirije a la raiz
            window.location.replace(base_url);
        }
    }

    function changePageLang(language) {
        let myLang = language["userPage"];

        $("#btnEnviarSolicitud").html(`<i class="bi bi-person-plus-fill"></i> ${myLang.btnEnviarSolicitud}`);
    }

    function sendInvitation(){
        let objData = {
            "_method": "inviteFriend",
            "userId": userId
        };

        $.post("../core/controllers/user.php", objData, function(result) {
            if(result.codeResponse == 200){
                alert("Invitation sent");
                reviewFriendRequest();
            }else{
                alert(result.message)
            }
        });
    }

    function reviewFriendRequest(){
        let objData = {
            "_method": "reviewFriendRequest",
            "userId": userId
        };

        $.post("../core/controllers/user.php", objData, function(result) {
            let intStatus = result.status[0];

            if(intStatus == 0){
                $("#btnEnviarSolicitud").removeClass("d-none");
            }else{
                $("#btnEnviarSolicitud").addClass("d-none");

                if(intStatus == 1){
                    $(".btnEnviado").removeClass("d-none");
                }else if(intStatus == 2){
                    $(".btnRecibido").removeClass("d-none");
                }else if(intStatus == 3){
                    $(".btnFriends").removeClass("d-none");
                    $(".lblDate").html(result.status[1]);
                }                
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