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
    <div class="container my-md-4 bd-layout">
        <div class="row justify-content-md-center">
            <div class="col-8">

                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title display-5 fw-bold lh-1 mb-3" id="lblName"></h5>
                                <p class="card-text lead" id="lblInfo"></p>
                                <p class="card-text"><small class="text-muted">List of registered teams</small></p>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Team name</th>
                                            <th scope="col">Status</th>
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
                        $("#lblName").html(`#${result.data.id}`);
                        $("#lblName").append(` ${result.data.name}`);

                        if(result.data.image){
                          $("#leaguePhoto").attr("src", `../${result.data.image}`);
                        }else{
                          $("#leaguePhoto").attr("src", `#`);
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
                                    <td><a href="http://localhost/clubtres/team/index.php?id=${item.id}" target="_blank" class="btn">${item.name}</a></td>
                                    <td class="text-center ${(item.status == 1) ? '' : 'text-danger'}">${(item.status == 1) ? 'Active' : 'Suspended'}</td>
                                </tr>
                            `;
                        });

                        $("#bdyTeams").html("");
                        $(rows).appendTo("#bdyTeams");

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

        function pad (str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }
    </script>
  </body>
</html>