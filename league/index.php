<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap, Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <title>League page | Clubtres</title>
  </head>
  <body>
    <div class="container">
      <div class="container col-xxl-8 px-4 py-5">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
          <div class="col-10 col-sm-8 col-lg-6">
            <img src="#" id="leaguePhoto"  class="d-block mx-lg-auto img-fluid shadow" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
          </div>
          <div class="col-lg-6">
            <h1 class="display-5 fw-bold lh-1 mb-3" id="lblName"></h1>
            <p class="lead" id="lblInfo">Informacion sobre la liga.</p>
            
          </div>
        </div>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Team Name</th>
            <th scope="col">options</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">43</th>
            <td>Kung Fu Pandas</td>
            <td><i class="bi bi-three-dots"></i></td>
          </tr>
          <tr>
            <th scope="row">342</th>
            <td>Cherrys</td>
            <td><i class="bi bi-three-dots"></i></td>
          </tr>
          <tr>
            <th scope="row">745</th>
            <td>Gatos Bravos</td>
            <td><i class="bi bi-three-dots"></i></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript">
        var leagueId = "<?php echo $_GET['id']; ?>";

        $(document).ready(function(){
            // Cargar datos de la liga
            fnLoadData();
        });

        function fnLoadData(){
            if(leagueId){
                let objData = {
                    "leagueId": leagueId
                };

                $.get("../core/controllers/league.php", objData, function(result) {
                    if(result.data){
                        $("#lblName").html(`${result.data.name}`);
                        $("#lblName").append(` #${result.data.id}`);

                        if(result.data.image){
                          $("#leaguePhoto").attr("src", `../${result.data.image}`);
                        }else{
                          $("#leaguePhoto").attr("src", `#`);
                        }

                        if(result.data.sport == 1){
                          $("#lblInfo").html("league of Soccer");
                        }else if(result.data.sport == 2){
                          $("#lblInfo").html("league of Basketball");
                        }else if(result.data.sport == 3){
                          $("#lblInfo").html("league of Football");
                        }
                    }else{
                        $("#lblName").html("");
                        $("#teamPhoto").attr("src", `#`);
                        $("#lblInfo").html("");
                    }
                });
            }else{
                window.location.replace("../index.html");
            }
            
        }
    </script>
  </body>
</html>