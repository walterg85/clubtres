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
                        $("#lblNombreTeam").html(`#${result.data.id}`);
                        $("#lblNombreTeam").append(` ${result.data.name}`);

                        if(result.data.image){
                          $("#teamPhoto").attr("src", `../${result.data.image}`);
                        }else{
                          $("#teamPhoto").attr("src", `#`);
                        }

                        $("#numMember").html(`${(result.data.teamlist).length} Members`);

                        let rows = '';
                        $.each(result.data.teamlist, function( index, item){
                            rows += `
                                <tr>
                                    <th scope="row">${pad(item.id, 5)}</th>
                                    <td><a href="http://localhost/clubtres/user/index.php?id=${item.id}" target="_blank" class="btn">${item.usName}</a></td>
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