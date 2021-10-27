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
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">

        <title>Account Page</title>
    </head>
    <style type="text/css">
        .dropdown-menu {
            width: 20rem !important;
        }
        td.details-control {
            background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
        }
        .offcanvas-end{
            width: 500px !important;
        }
    </style>
    <body>
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 lblTitle" href="javascript:void(0);">Clubtres</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="javascript:void(0);" id="btnLogout">Sign out</a>
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
                                    <i class="bi bi-house-door-fill"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link changeLang" href="javascript:void(0);">                                    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" id="linkLeague">
                                    <i class="bi bi-people-fill"></i> Leagues
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" id="linkTeam">
                                    <i class="bi bi-person-badge-fill"></i> Teams
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" id="linkInvitation">
                                    <i class="bi bi-bell-fill"></i> Invitations <span class="badge bg-danger bdg-Notification">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" id="linkGames">
                                    <i class="bi bi-cone-striped"></i> Games
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" id="linkBusiness">
                                    <i class="bi bi-award"></i> Business
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pb-4" id="mainContenedor"></main>
            </div>
        </div>

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Datatables -->
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

        <script type="text/javascript">
            var currentLeague = null,
                lang = (window.navigator.language).substring(0,2),
                currentSection = "";
            
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

                $("#linkInvitation").on("click", function(){
                    $( "#mainContenedor" ).load( `invitation.html?v=${Math.random()}` );
                });

                $("#btnLogout").on("click", function(){
                    if (confirm(`do you really want to log out?`)){
                        localStorage.removeItem("logged");
                        window.location.replace("logout.php");
                    }
                });

                $("#linkGames").on("click", function(){
                    $( "#mainContenedor" ).load( `game.html?v=${Math.random()}` );
                });

                $("#linkBusiness").on("click", function(){
                    $( "#mainContenedor" ).load( `business.html?v=${Math.random()}` );
                });

                findNotifications();
                setInterval(findNotifications, 2500);

                $("#linkHome").click();

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

            function getData(obj, dtable){
                let tr   = obj.parents("tr");
                return dtable.row( tr ).data();
            }

            function pad (str, max) {
                str = str.toString();
                return str.length < max ? pad("0" + str, max) : str;
            }

            function findNotifications(){
                let objData = {
                    "_method": "countInvitation"
                };

                $.post("../core/controllers/user.php", objData, function(result) {
                    $(".bdg-Notification").html(result.count);
                }).fail( function(jqXHR, textStatus, errorThrown){
                    ajaxResponseError(jqXHR, textStatus);
                });
            }

            function ajaxResponseError(jqXHR, textStatus){
                let msgError = "";

                if (jqXHR.status === 0) {
                    msgError ="Not connect.\n Verify Network.";
                } else if (jqXHR.status == 404) {
                    msgError ="Requested page not found. [404]";
                } else if (jqXHR.status == 500) {
                    msgError ="Internal Server Error [500].";
                } else if (jqXHR.status == 401) {
                    localStorage.removeItem("logged");
                    msgError ="Unauthorized, the user does not have access to the information because they do not have the credentials";
                    window.location.replace("../index.html");
                } else if (textStatus === 'parsererror') {
                    msgError ="Requested JSON parse failed.";
                } else if (textStatus === 'timeout') {
                    msgError ="Time out error.";
                } else if (textStatus === 'abort') {
                    msgError ="Ajax request aborted.";
                } else {
                    msgError =`Uncaught Error.\n ${jqXHR.responseText}`;
                }

                console.log(msgError);
            }

            function switchLanguage(lang){
            $.post("../assets/languages.json", {}, function(data) {
                $(".changeLang").html('<i class="bi bi-globe2"></i> ' + data[lang]["buttonText"]);

                let myLang = data[lang]["main"];

                $(".lblTitle").html(myLang.title);
                $("#btnLogout").html(myLang.logout);

                $("#linkHome").html(`<i class="bi bi-house-door-fill"></i> ${myLang.linkHome}`);
                $("#linkLeague").html(`<i class="bi bi-people-fill"></i> ${myLang.linkLeague}`);
                $("#linkTeam").html(`<i class="bi bi-person-badge-fill"></i> ${myLang.linkTeam}`);
                $("#linkInvitation").html(`<i class="bi bi-bell-fill"></i> ${myLang.linkInvitation} <span class="badge bg-danger bdg-Notification">0</span>`);
                $("#linkGames").html(`<i class="bi bi-cone-striped"></i> ${myLang.linkGames}`);
                $("#linkBusiness").html(`<i class="bi bi-award"></i> ${myLang.linkBusiness}`);

                myLang = data[lang][currentSection];
                switch(currentSection){
                    case "home":
                       $(".lblMyTeam").html();
                       $(".teamAlert").find(".blockquote").html();

                       $(".lblMyLeague").html();
                       $(".leagueAlert").find(".blockquote").html();
                    break;
                }
            });
        }
        </script>
    </body>
</html>
