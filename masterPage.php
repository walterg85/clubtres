<?php
    // Se inicia el metodo de session par hacer uso de las variables
    @session_start();

    // Url raiz, para todas las coneciones al controlador, este se debe cambiar cuando se publica el proecto con una DNS
    $base_url = 'http://localhost/clubtres';
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap, Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <title>Clubtres</title>
    </head>
<body>
    <!-- Header -->
    <header class="p-3 mb-3 bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start ">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-decoration-none text-white">
                    <?php if(isset($_SESSION['login'])) { ?>
                        <span class="fs-4 text-secondary">Clubtres <small>id: <?php echo str_pad($_SESSION['authData']->id, 5, "0", STR_PAD_LEFT); ?></small></span>
                    <?php } else{ ?>
                        <span class="fs-4 text-secondary">Clubtres</span>
                    <?php } ?>
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0"></ul>

                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                    <!-- <input type="search" class="form-control" placeholder="Search..." aria-label="Search"> -->
                    <div class="dropdown">
                        <input class="form-control dropdown-toggle" type="text" placeholder="Search" id="inputSearch" autocomplete="off">
                        <ul class="dropdown-menu" id="cboMenu" aria-labelledby="inputSearch"></ul>
                    </div>
                </form>

                <?php
                    // Se verifica que este logueado e imprimir su informacion, de lo contrario se mostrara el boton para iniciar session
                    if(isset($_SESSION['login'])){
                ?>
                    <div class="dropdown text-end">
                        <a href="#" class="d-block text-decoration-none dropdown-toggle text-white" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Se usa rand() para generar un numero aleatroio y forzar a la carga de imagen -->
                            <img src="<?php echo $base_url . '/' . $_SESSION['authData']->image .'?v='.rand(0, 15); ?>" alt="mdo" width="32" height="32" class="rounded-circle me-2">
                            <texto class="lableSaludo">Hi</texto> <?php echo $_SESSION['authData']->name; ?> 
                        </a>
                        <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1" style="">
                            <li><a class="dropdown-item linkSettings" href="<?php echo $base_url; ?>/account/index.php?link=linkSettings">Settings</a></li>
                            <li><a class="dropdown-item changeLang" href="javascript:void(0);">Español</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" id="btnLogout" href="javascript:void(0);">Sign out</a></li>
                        </ul>
                    </div>
                <?php
                    } else {
                ?>
                    <div class="text-end">
                        <a href="<?php echo $base_url; ?>" class="d-block text-decoration-none text-white linkLogin">
                            Sign in
                        </a>
                    </div>
                <?php 
                    }
                ?>
            </div>
        </div>
    </header>

    <!-- Body: Aqui se imprime todo el contedido de las paginas secundarias capturados del buffer -->
    <?php echo $content; ?>

    <!-- Footer -->
    <footer class="text-center mt-5  pb-2">
        <ul class="list-inline mb-1">
            <li class="list-inline-item">&copy; <script>document.write(new Date().getFullYear())</script> CLUBTRES</li>
            <li class="list-inline-item">
                <a class="text-secondary text-decoration-none changeLang" href="javascript:void(0);"></a>
            </li>
        </ul>
        <p>
            <text class="footerNote">A project of</text>
            <a class="text-warning text-decoration-none" target="_blank" href="https://www.intelatlas.com">INTELATLAS</a>
        </p>
    </footer>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript">
        // Se comparte la variable gobal de raiz para cargar contenido de lado cliente
        var base_url = "<?php echo $base_url; ?>",
            searchRequest = null,
            lang = (window.navigator.language).substring(0,2);

        $(document).ready(function(){
            // Metodo para el cierre de session
            $("#btnLogout").on("click", function(){
                if (confirm(`do you really want to log out?`)){
                    localStorage.removeItem("logged");
                    window.location.replace(`${base_url}/account/logout.php`);
                }
            });

            // Se agrega la accion de buscar cada vez que se presiona una tecla
            $('#inputSearch').keyup(function(){
                if(searchRequest)
                    searchRequest.abort();

                searchRequest = $.ajax({
                    url:`${base_url}/core/controllers/user.php`,
                    method:"POST",
                    data:{
                        _method:'search',
                        strQuery: $('#inputSearch').val()
                    },
                    success:function(data){
                        let items = '',
                            corte = '',
                            link = '';
                        $.each(data.data, function(index, result){
                            if(corte != result.tipo){
                                items += `<h6 class="dropdown-header"><strong>${result.tipo}</strong></h6>`;
                                corte = result.tipo;

                                if(corte == 'League'){
                                    link = 'league/index.php';
                                }else if(corte == 'Business'){
                                    link = 'business/index.php';
                                }else if(corte == 'User'){
                                    link = 'user/index.php';
                                }else if(corte == 'Team'){
                                    link = 'team/index.php';
                                }
                            }

                            items += `
                                <li>
                                    <a class="dropdown-item ps-5" href="${base_url}/${link}?id=${result.id}" target="_blank">
                                        <!-- <img src="#" alt="twbs" height="32" class="rounded flex-shrink-0 me-2"> -->
                                        ${result.nombre}
                                    </a>
                                </li>
                            `;
                        });

                        $("#cboMenu")
                            .html(items)
                            .addClass("show");
                    }
                });
            });

            $('body').click(function() {
                if(searchRequest)
                    searchRequest.abort();

                $("#cboMenu")
                    .html("")
                    .removeClass("show");
            });

            if( localStorage.getItem("currentLag") ){
                lang = localStorage.getItem("currentLag");
            }else{
                localStorage.setItem("currentLag", lang);
            }

            $(".changeLang").click( function(){
                if (localStorage.getItem("currentLag") == "es") {
                    localStorage.setItem("currentLag", "en");
                    lang = "en";
                }else{
                    localStorage.setItem("currentLag", "es");
                    lang = "es";
                }
                switchLanguage(lang);
            });

            switchLanguage(lang);
        });

        // Metodo para setear digitos a la izquierda
        function pad (str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }

        function switchLanguage(lang){
            let allLang = null;

            $.post(`${base_url}/core/controllers/language.php`, {}, function(data) {
                allLang = data[lang];
                
                $(".changeLang").html(`<i class="bi bi-globe"></i> ${data[lang]["buttonText"]}`);
                $(".lableSaludo").html(`${data[lang]["lableSaludo"]}`);

                let myLang = data[lang]["main"];

                $(".linkSettings").html(`<i class="bi bi-wrench"></i> ${myLang.linkSettings}`);
                $("#btnLogout").html(`<i class="bi bi-shield-lock-fill"></i> ${myLang.logout}`);
                $("#inputSearch").attr("placeholder", myLang.inputSearch);

                $(".footerNote").html(data[lang]["login"].footerNote);
                $(".linkLogin").html(myLang.login);
            }).always(function() {
                changePageLang(allLang);
            });
        }
    </script>
</body>
</html>