<?php
    session_start();

    if(!isset($_SESSION['login'])){
        echo '
            <script type="text/javascript">
                localStorage.removeItem("logged");
                window.location.replace("logout.php");
            </script>
        ';

        exit();
    }
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
        /*Forzar a que se muestre el fondo blanco en el modal del cropperJS*/
        .cropper-modal{
            background-color: #fff !important;
            opacity: .7 !important;
        }
        .globoChat {
            width: 80px;
            height: 80px;
            border-radius: 50px;
            display: flex;
            justify-content: center;
            position: absolute;
            bottom: 30px;
            cursor: pointer;
            background-color: #b7b7b7;
            transition: all 0.4s
        }

        .wrapper {
            position: absolute;
            bottom: 100px;
            width: 300px;
            background-color: #383b3f;
            border-radius: 5px;
            opacity: 0;
            transition: all 0.4s;
            z-index: 9;
        }

        .header {
            padding: 13px;
            background-color: #191c1f;
            border-radius: 5px 5px 0px 0px;
            margin-bottom: 10px;
            color: #fff
        }

        .chat-form {
            padding: 15px
        }

        .chat-form input, textarea, button {
            margin-bottom: 10px
        }

        .chat-form textarea {
            resize: none
        }

        .form-control:focus, .btn:focus {
            box-shadow: none
        }

        #chatLog {
            text-align: left;
            margin: 0 auto;
            padding: 10px;
            height: 250px;
            width: 100%;
            overflow: auto;
        }

    </style>
    <body>
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 text-secondary" href="#">Clubtres <small>id: 87576</small></a>
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
                    <img src="<?php echo '../' . $_SESSION['authData']->image .'?v='.rand(0, 15); ?>" alt="mdo" width="32" height="32" class="rounded-circle me-2 userImg">
                    <texto class="lableSaludo">Hi</texto> <?php echo $_SESSION['authData']->name; ?> 
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
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" id="linkFriends">
                                    <i class="bi bi-file-person"></i> Friends
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" id="linkSettings">
                                    <i class="bi bi-wrench"></i> Settings
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pb-4" id="mainContenedor"></main>

                <!-- Contenedor para burbujas de chats activos -->
                <div id="chatContain">
                    <div class="wrapper">
                        <div class="header">
                            <h6 class="labelChatTitle">Nombre de usuario</h6>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger btnCloceChat">
                                X
                            </span>
                        </div>
                        <div class="text-center p-2">
                            <div id="chatLog"></div>
                        </div>
                        <div class="chat-form">
                            <div id="divConversasion">
                                <textarea class="form-control" placeholder="Your Message" id="inputNewMessage"></textarea>
                                <div class="d-md-flex justify-content-md-end">
                                    <button class="btn btn-success btn-block pull-right" id="btnSendmessage">Send</button>
                                </div>                                
                            </div>
                        </div>
                    </div>

                    <div class="globoChat globoClone d-none">
                        <img src="#" class="rounded-circle usAvatar" data-bs-toggle="tooltip">
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger btnCloceChatGlobo">
                            x
                        </span>
                    </div>
                </div>
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
                currentSection = "",
                searchRequest = null,
                actualLenguaje = null,
                linkto = null,
                countChat = 0,
                refreshLog = null,
                useridChat = 0,
                sincroniceLog = null;
            
            $(document).ready(function(){
                let queryString = window.location.search,
                    urlParams = new URLSearchParams(queryString);
                linkto = urlParams.get('link');

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

                $("#linkSettings, .linkSettings").on("click", function(){
                    $( "#mainContenedor" ).load( `setting.html?v=${Math.random()}` );
                });

                $("#linkFriends").on("click", function(){
                    $( "#mainContenedor" ).load( `friends.html?v=${Math.random()}` );
                });

                findNotifications();
                setInterval(findNotifications, 2500);

                loadUnreadChat();
                setInterval(loadUnreadChat, 3000);

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

                $('#inputSearch').keyup(function(){
                    if(searchRequest)
                        searchRequest.abort();

                    searchRequest = $.ajax({
                        url:"../core/controllers/user.php",
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
                                        link = '../league/index.php';
                                    }else if(corte == 'Business'){
                                        link = '../business/index.php';
                                    }else if(corte == 'User'){
                                        link = '../user/index.php';
                                    }else if(corte == 'Team'){
                                        link = '../team/index.php';
                                    }
                                }

                                items += `
                                    <li>
                                        <a class="dropdown-item ps-5" href="${link}?id=${result.id}" target="_blank">
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

                // Validar si hay un link de acceso directo a las paginas o simplemente mostrar la pagina inicial
                if(linkto){
                    $(`#${linkto}`).click();
                }else{
                    $("#linkHome").click();
                }

                $("#btnSendmessage").click( function () {
                    let objData = {
                        "_method": "POST",
                        "message": $("#inputNewMessage").val(),
                        "destiny": useridChat
                    };

                    $.post("../core/controllers/chat.php", objData, function(result){
                        $("#inputNewMessage").val("");
                    });
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

            function loadUnreadChat(){
                let objData = {
                    "_method": "loadUnreadChat"
                };

                $.post("../core/controllers/chat.php", objData, function(result) {
                    let info = result.chats;

                    if(info.length > 0){
                        let data = {};

                        if(result.originalOrigin == info[0].origin){
                            let contenedor = $("#chatContain").find(`.friendid-${info[0].destiny}`);
                            if(contenedor.length > 0){
                                $(`.friendid-${info[0].destiny}`).click();
                                return;
                            }                             

                            data.friend_id  = info[0].destiny;
                            data.avatar     = info[0].destinyAvatar;
                            data.name       = info[0].destinyName;
                        } else{
                            let contenedor = $("#chatContain").find(`.friendid-${info[0].origin}`);
                            if(contenedor.length > 0){
                                $(`.friendid-${info[0].origin}`).click();
                                return;
                            } 

                            data.friend_id  = info[0].origin;
                            data.avatar     = info[0].originAvatar;
                            data.name       = info[0].originName;
                        }

                        invoqueChat(data)
                    }
                    
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
                    $(".lableSaludo").html(`${data[lang]["lableSaludo"]}`);
                
                    let myLang = data[lang]["main"];
                    
                    $("#btnLogout").html(`<i class="bi bi-shield-lock-fill"></i> ${myLang.logout}`);
                    $("#inputSearch").attr("placeholder", myLang.inputSearch);

                    $("#linkHome").html(`<i class="bi bi-house-door-fill"></i> ${myLang.linkHome}`);
                    $("#linkLeague").html(`<i class="bi bi-people-fill"></i> ${myLang.linkLeague}`);
                    $("#linkTeam").html(`<i class="bi bi-person-badge-fill"></i> ${myLang.linkTeam}`);
                    $("#linkInvitation").html(`<i class="bi bi-bell-fill"></i> ${myLang.linkInvitation} <span class="badge bg-danger bdg-Notification">0</span>`);
                    $("#linkGames").html(`<i class="bi bi-cone-striped"></i> ${myLang.linkGames}`);
                    $("#linkBusiness").html(`<i class="bi bi-award"></i> ${myLang.linkBusiness}`);
                    $("#linkSettings, .linkSettings").html(`<i class="bi bi-wrench"></i> ${myLang.linkSettings}`);
                    $("#linkFriends").html(`<i class="bi bi-file-person"></i> ${myLang.linkFriends}`);

                    $("#btnSendmessage").html(myLang.btnSendmessage);
                    $("#inputNewMessage").attr("placeholder", myLang.inputNewMessage);

                    // se setea en la variable global el JSON de idioma
                    actualLenguaje = data[lang];
                }).done(function() {

                    // Validar si ya esta cargada una seccion para ejecutar el metodo de cambio de idioma
                    if(currentSection)
                        changePageLang();
                });
            }

            function loadLog(){
                let objData = {
                    "_method": "_Getlog",
                    "destiny": useridChat
                };

                oldscrollHeight = $("#chatLog")[0].scrollHeight - 20;

                $.post("../core/controllers/chat.php", objData, function(result){
                    if(result.log){
                        $("#chatLog").html(result.log.message);

                        if(result.log.unread == result.myUserid){
                            let objData = {
                                "_method": "checkChat",
                                "chatId": result.log.id
                            };

                            $.post("../core/controllers/chat.php", objData);
                        }                        
                    }

                    let newscrollHeight = $("#chatLog")[0].scrollHeight - 20;
                    if(newscrollHeight > oldscrollHeight)
                        $("#chatLog").animate({ scrollTop: newscrollHeight }, 'normal');
                });
            }

            function invoqueChat(allInfo){
                let globo    = $(".globoClone").clone(),
                    data     = allInfo,
                    cssLeft = (countChat == 0) ? 340 : 340 + (countChat * 100);

                useridChat   = data.friend_id;

                globo.find(".usAvatar").attr("src", `../${data.avatar}`);                            
                globo.find(".usAvatar").attr("title", data.name);

                globo.find(".usAvatar").attr("data-friendid", data.friend_id);
                globo.find(".usAvatar").addClass(`friendid-${data.friend_id}`)

                globo.removeClass("d-none globoClone");
                globo.css('left', cssLeft);

                $(globo).appendTo("#chatContain");
                countChat += 1;

                //Tooltips para globos del chat
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                // Accion para cerrar chat
                $(".btnCloceChat").unbind().click( function(){
                    $(".wrapper").css("opacity", 0);
                    $(".wrapper").css("left", 0);
                    $(".labelChatTitle").html("");

                    clearInterval(sincroniceLog);
                });

                $(".btnCloceChatGlobo").unbind().click( function(){
                    $(this).parent().remove();
                    countChat -= 1;
                });

                $(".wrapper").css("opacity", 1);
                $(".wrapper").css("left", cssLeft);
                $(".labelChatTitle").html(data.name);

                loadLog();
                sincroniceLog = setInterval(loadLog, 2000);

                $(".usAvatar").unbind().click( function(){
                    clearInterval(sincroniceLog);

                    let leftPx = $(this).parent().css("left");
                    useridChat = $(this).data("friendid");

                    $(".wrapper").css("opacity", 1);
                    $(".wrapper").css("left", leftPx);

                    loadLog();
                    sincroniceLog = setInterval(loadLog, 2000);
                });
            }
        </script>
    </body>
</html>
