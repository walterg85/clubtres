<?php
    /*session_start();

    if(!isset($_SESSION['login'])){
        echo '
            <script type="text/javascript">
                localStorage.removeItem("logged");
                window.location.replace("logout.php");
            </script>
        ';

        exit();
    }*/
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap, CSS & Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="../assets/css/account.min.css" rel="stylesheet" >
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

        <!-- Datatables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">

        <!-- Admin CSS -->
        <link rel="stylesheet" href="../assets/css/admin.min.css">

        <!-- sweetalert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11" async></script>

        <title>Admin Page</title>
    </head>
    <style type="text/css">
        

    </style>
    <body>
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 text-secondary" href="javascript:void(0);">Clubtres <small>Admin</small></a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="dropdown" style="width: 100%">
                <input class="form-control form-control-dark w-100 dropdown-toggle" type="text" placeholder="Search" id="inputSearch" autocomplete="off">
                <ul class="dropdown-menu" id="cboMenu" aria-labelledby="inputSearch"></ul>
            </div>

            <!-- <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="javascript:void(0);" id="btnLogout">Sign out</a>
                </div>
            </div> -->

            <div class="dropdown text-end mx-2">
                <a href="#" class="d-block text-decoration-none dropdown-toggle text-white" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- Se usa rand() para generar un numero aleatroio y forzar a la carga de imagen -->
                    <!-- <img src="#" alt="mdo" width="32" height="32" class="rounded-circle me-2 userImg"> -->
                    <texto class="lableSaludo">Hi</texto> Admin
                </a>
                <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1" style="">
                    <li><a class="dropdown-item linkSettings" href="javascript:void(0);">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" id="btnLogout" href="javascript:void(0);">Sign out</a></li>
                </ul>
            </div>
        </header>

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" id="linkHome">
                                    <i class="bi bi-house-door-fill"></i> Home
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pb-4" id="mainContenedor">
                    <!-- Body: Aqui se imprime todo el contedido de las paginas secundarias capturados del buffer -->
                    <?php echo $content; ?>
                </main>
            </div>
        </div>

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Datatables -->
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" async></script>
        <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js" async></script>

        <script type="text/javascript">            
            $(document).ready(function(){
                

            });

            // Metodo para mostrar una alerta de notificaicon
            // icon: success || error
            // text: texto que se mostrara en pantalla
            function showAlert(icon, text){
                Swal.fire({
                    position: 'top-end',
                    icon: icon,
                    text: text,
                    showConfirmButton: false,
                    timer: 3000
                });
            }

            // Metodo para mostrar una alerta de confirmacion
            // title: Cuestion proincipal
            // text: Texto explicativo
            // confirmButtonText: texto que se colocara en el boton de confirmacion
            /*
            [USAGE]
            (async () => {
                const alert = await showConfirmation("¿Deseas eliminar?", "Esto no se podra revertir!", "Si eliminar");
                console.log(alert);
            })()

            [RESULT]
            {
                "isConfirmed": false,
                "isDenied": false,
                "isDismissed": true,
                "dismiss": "cancel"
            }
            */
            function showConfirmation(title, text, confirmButtonText){
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmButtonText,
                    allowOutsideClick: false
                });
            }
        </script>
    </body>
</html>
