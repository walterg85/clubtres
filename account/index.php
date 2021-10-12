<?php
    session_start();

    if(!isset($_SESSION['login']))
        header("Location: ../index.html");
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap, CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="../assets/css/account.css" rel="stylesheet" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <title>Account Page</title>
  </head>
  <body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Clubtres</a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
      <div class="navbar-nav">
        <div class="nav-item text-nowrap">
          <a class="nav-link px-3" href="#">Sign out</a>
        </div>
      </div>
    </header>

    <div class="container-fluid">
      <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
          <div class="position-sticky pt-3">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-house-door-fill"></i>
                  Home
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-people-fill"></i>
                  Leagues
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-person-badge-fill"></i>
                  Teams
                </a>
              </li>
            </ul>

          </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
              <h1 class="h2">Teams</h1>
              <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addTeamModal">Create new team</button>
                  
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">options</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1010</td>
                    <td>The Incrdibles</td>
                    <td><i class="bi bi-eye-fill"></i></td>
                  </tr>
                  <tr>
                    <td>1013</td>
                    <td>La Galaxy</td>
                    <td><i class="bi bi-eye-fill"></i></td>
                  </tr>
                  <tr>
                    <td>1014</td>
                    <td>Cherry</td>
                    <td><i class="bi bi-eye-fill"></i></td>
                  </tr>
                  <tr>
                    <td>1015</td>
                    <td>White Socks</td>
                    <td><i class="bi bi-eye-fill"></i></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
              <h1 class="h2">Leaugues</h1>
              <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addLeagueModal">Create new leag</button>
                  
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">options</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1010</td>
                    <td>The Incrdibles</td>
                    <td><i class="bi bi-eye-fill"></i></td>
                  </tr>
                  <tr>
                    <td>1013</td>
                    <td>La Galaxy</td>
                    <td><i class="bi bi-eye-fill"></i></td>
                  </tr>
                  <tr>
                    <td>1014</td>
                    <td>Cherry</td>
                    <td><i class="bi bi-eye-fill"></i></td>
                  </tr>
                  <tr>
                    <td>1015</td>
                    <td>White Socks</td>
                    <td><i class="bi bi-eye-fill"></i></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
        </main>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add new team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="frmTeam">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Team Name</label>
                            <input type="text" name="inputName" class="form-control" id="inputName">              
                        </div>
                        <div class="mb-3">
                            <label for="imageteam" class="form-label">Team image</label>
                            <input class="form-control form-control-sm" id="imageteam" name="imageteam" type="file" accept="image/*">
                        </div>
                        <button type="button" class="btn btn-primary" id="btnRegisterTeam">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addLeagueModal" tabindex="-1" aria-labelledby="modalTitle2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle2">Add new league</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="frmLeague">
                        <div class="mb-3">
                            <label for="inputNameL" class="form-label">League Name</label>
                            <input type="text" name="inputNameL" class="form-control" id="inputNameL">              
                        </div>
                        <select class="form-select mb-3" name="sportType" id="sportType" aria-label="Default select example">
                            <option value="0" selected>Select sport</option>
                            <option value="1">Soccer</option>
                            <option value="2">Basketball</option>
                            <option value="3">Football</option>
                        </select>
                        <div class="mb-3">
                            <label for="imageleague" class="form-label">League image</label>
                            <input class="form-control form-control-sm" id="imageleague" name="imageleague" type="file" accept="image/*">
                        </div>
                        <button type="button" class="btn btn-primary" id="btnRegisterLeague">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            // Registrar nuevo equipo
            $("#btnRegisterTeam").on("click", fnRegisterTeam);

            // Registrar nueva liga
            $("#btnRegisterLeague").on("click", fnRegisterLeague);

            // Controlar tipo de objeto que intentan subir
            $('input[type="file"]').on('change', function(){
                let ext = $( this ).val().split('.').pop();

                if ($( this ).val() != ''){
                    if($.inArray(ext, ["jpg", "jpeg", "png", "bmp", "raw", "tiff"]) != -1){
                        if($(this)[0].files[0].size > 1048576){
                            $( this ).val('');
                            alert('Your selected file is larger than 1MB');
                        }
                    }else{
                        $( this ).val('');
                        alert(`${ext} files not allowed, only images`);
                    }
                }
            });
        });

        function fnRegisterTeam(){
            let form = $("#frmTeam")[0],
                formData = new FormData(form);

            formData.append('_method', "POST");

            $.ajax({
                url: '../core/controllers/team.php',
                data: formData,
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    alert(response.message);
                    $("#addTeamModal").modal("hide");
                    $("#frmTeam")[0].reset()
                },
                error: function(xhr, status) {
                    alert('There is a problem');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        function fnRegisterLeague(){
            let form = $("#frmLeague")[0],
                formData = new FormData(form);

            formData.append('_method', "POST");

            $.ajax({
                url: '../core/controllers/league.php',
                data: formData,
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    alert(response.message);
                    $("#addLeagueModal").modal("hide");
                    $("#frmLeague")[0].reset()
                },
                error: function(xhr, status) {
                    alert('There is a problem');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    </script>
  </body>
</html>
