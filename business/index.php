<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap, Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <title>Business page | Clubtres</title>
</head>
<body>
    <div class="container my-md-4 bd-layout">
        <div class="row justify-content-md-center">
            <div class="col-8">

                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title display-5 fw-bold lh-1 mb-3" id="lblName"></h5>
                                <p class="card-text lead" id="lblInfo"></p>
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


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

                $.get("../core/controllers/business.php", objData, function(result) {
                    if(result.data){
                        $("#lblName").html(`#${result.data.id}`);
                        $("#lblName").append(` ${result.data.nombre}`);

                        if(result.data.image){
                          $("#businessPhoto").attr("src", `../${result.data.image}?v=${Math.random()}`);
                        }else{
                          $("#businessPhoto").attr("src", `#`);
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
                window.location.replace("../index.html");
            }            
        }

        function pad (str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }
    </script>
  </body>
</html>