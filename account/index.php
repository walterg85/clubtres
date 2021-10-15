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

    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <title>Account Page</title>
  </head>
  <style type="text/css">
    .dropdown-menu {
        width: 20rem !important;
    }
  </style>
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
                <a class="nav-link" href="javascript:void(0);" id="linkHome">
                  <i class="bi bi-house-door-fill"></i>
                  Home
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" id="linkLeague">
                  <i class="bi bi-people-fill"></i>
                  Leagues
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" id="linkTeam">
                  <i class="bi bi-person-badge-fill"></i>
                  Teams
                </a>
              </li>
            </ul>

          </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="mainContenedor"></main>
      </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Datatables -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#linkHome").on("click", function(){
                $( "#mainContenedor" ).load( `home.html?v=${Math.random()}` );
            });

            $("#linkTeam").on("click", function(){
                $( "#mainContenedor" ).load( `team.html?v=${Math.random()}` );
            });

            $("#linkLeague").on("click", function(){
                $( "#mainContenedor" ).load( `league.html?v=${Math.random()}` );
            });
        });

        function getData(obj, dtable){
            let tr   = obj.parents("tr");
            return dtable.row( tr ).data();
        }

        function pad (str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }
    </script>
  </body>
</html>
