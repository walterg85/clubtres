<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Team page | Clubtres</title>
  </head>
  <body>
    <div class="container">
      <img class="img-thumbnail shadow" src="#" id="teamPhoto" width="150px">
      <h1 class="text-secondary my-3" id="lblNombreTeam"></h1>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">43</th>
            <td>Mark Ortiz</td>
            <td>active</td>
          </tr>
          <tr>
            <th scope="row">342</th>
            <td>Jacob Smith</td>
            <td>suspended</td>
          </tr>
          <tr>
            <th scope="row">745</th>
            <td>Mario Velasuqez</td>
            <td>active</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

                $.get("../core/controllers/team.php", objData, function(result) {
                    if(result.data){
                        $("#lblNombreTeam").html(`${result.data.name}`);
                        $("#lblNombreTeam").append(` #${result.data.id}`);

                        if(result.data.image){
                          $("#teamPhoto").attr("src", `../${result.data.image}`);
                        }else{
                          $("#teamPhoto").attr("src", `#`);
                        }
                    }else{
                        $("#lblNombreTeam").html("");
                        $("#teamPhoto").attr("src", `#`);
                    }
                });
            }else{
                window.location.replace("../index.html");
            }
            
        }
    </script>
  </body>
</html>