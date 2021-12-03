<?php
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
                            <p class="mb-0 labelAbout">About</p>
                            <p class="card-text lead mt-0" id="lblInfo"></p>
                            <p class="card-text"><small class="text-muted lblDir"></small></p>
                            <p class="card-text" id="Contact"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img src="#" class="img-fluid rounded-start" alt="Business photo" id="businessPhoto">
                    </div>
                </div>
            </div>

        </div>
    </div>       
</div>

<script type="text/javascript">
    var businessId = "<?php echo $_GET['id']; ?>";

    $(document).ready(function(){
        // Cargar datos de business
        fnLoadData();
    });

    function fnLoadData(){
        if(businessId){
            let objData = {
                "businessId": businessId
            };

            $.get(`${base_url}/core/controllers/business.php`, objData, function(result) {
                if(result.data){
                    $("#lblName").html(`#${result.data.id}`);
                    $("#lblName").append(` ${result.data.nombre}`);

                    if(result.data.image){
                      $("#businessPhoto").attr("src", `${base_url}/${result.data.image}?v=${Math.random()}`);
                    }else{
                      $("#businessPhoto").attr("src", `https://www.distribucionactualidad.com/wp-content/uploads/2019/05/grow-my-store.png`);
                    }

                    $("#lblInfo").html(`${result.data.Descripcion}`);
                    $(".lblDir").html(`${result.data.Direccion}`);
                    $("#Contact").html(`<small class="text-muted">${result.data.telefono} | ${result.data.web}</small>`);
                }else{
                    $("#lblName").html("");
                    $("#teamPhoto").attr("src", `#`);
                    $("#lblInfo").html("");
                    $(".lblDir").html("");
                    $("#Contact").html("");
                }
            });
        }else{
            window.location.replace(base_url);
        }            
    }

    function changePageLang(language) {
        let myLang = language["businessPage"];

        $(".labelAbout").html(`${myLang.labelAbout}`);
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