var lang = (window.navigator.language).substring(0,2);

$(document).ready(function(){
    // Registrar nuevo usuario
    $("#btnRegister").on("click", fnRegisterUser);

    // Activar solicitud de restableciiento
    $("#btnRestore").click( fnRestorePassword);

    // Validar inicio de session
    $("#btnLogin").on("click", fnValidateUser);

    // Cuando se hace enter en el inputpassword se ejecuta el metodo de validar login
    $('#inputPassword').keypress(function(e) {
        if(e.which == 13)
            fnValidateUser();
    });

    // si ya esta logueado redirigirlo a Home
    if(localStorage.getItem("logged"))
            window.location.replace("account/index.php");

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

function fnRegisterUser(){
    let forms = document.querySelectorAll('.needs-validation'),
            continuar = true;

    Array.prototype.slice.call(forms).forEach(function (form){ 
            if (!form.checkValidity()) {
                    continuar = false;
            }

            form.classList.add('was-validated');
    });

    if(!continuar)
            return false;

    let objData = {
            "_method": "POST",
            "name": $("#inputRegName").val(),
            "last_name": $("#inputRegLastName").val(),
            "email": $("#inputRegEmail").val(),
            "password": $("#inputRegPassword").val()
    };

    $.post("core/controllers/user.php", objData, function(result) {
            if(result.codeResponse == 200){
                    $("#inputEmail").val( $("#inputRegEmail").val() );
                    $("#inputPassword").val( $("#inputRegPassword").val() );

                    fnValidateUser();

                    $("#registerModal").modal("hide");
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: "info",
                        text: result.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
    });
}

function fnRestorePassword(){
    let forms = document.querySelectorAll('.needs-validation-restore'),
        continuar = true;

    Array.prototype.slice.call(forms).forEach(function (form){ 
            if (!form.checkValidity()) {
                    continuar = false;
            }

            form.classList.add('was-validated');
    });

    if(!continuar)
            return false;

    let objData = {
        "_method": "_RestorePassword",
        "email": $("#inputRestoreMail").val()
    };

    $.post("core/controllers/user.php", objData, function(result) {
        if(result.data.existe == 0){
            Swal.fire({
                position: 'top-end',
                icon: "warning",
                text: "The user is not registered",
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            $("#inputRestoreMail").val("");
            $("#restoreModal").modal("hide");
            Swal.fire({
                position: 'top-end',
                icon: "success",
                text: "An email was sent with the league to reset your password",
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
}

function fnValidateUser(){
    let objData = {
            "_method": "VALIDATE",
            "email": $("#inputEmail").val(),
            "password": $("#inputPassword").val()
    };

    $.post("core/controllers/user.php", objData, function(result) {
            if(result.codeResponse == 200){
                    localStorage.setItem("logged", true);
                    window.location.replace("account/index.php");
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: "info",
                    text: result.message,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
    });
}

function switchLanguage(lang){
    $.post("core/controllers/language.php", {}, function(data) {
        $(".changeLang").html('<i class="bi bi-globe2"></i> ' + data[lang]["buttonText"]);

        let myLang = data[lang]["login"];

        // Formulario principal
        $("#punchLine").html(myLang.punchLine);
        $("#inputEmail").attr("placeholder", myLang.inpuitmail);
        $("#inputPassword").attr("placeholder", myLang.inputPass);
        $(".lblCheck").html(`<input type="checkbox" value="remember-me"> ${myLang.inputCheck}`);
        $("#btnLogin").html(myLang.button + ' <i class="bi bi-box-arrow-in-right"></i>');
        $(".lblNewAccount").html(myLang.link);
        $(".footerNote").html(myLang.footerNote);
        
        $("#inputRestoreMail").attr("placeholder", myLang.inputRestoreMail);
        $("#btnRestore").html(myLang.btnRestore);
        
        // Modal
        $("#exampleModalLabel").html(myLang.modalTitle);
        $("#inputRegName").attr("placeholder", myLang.mdlInputName);
        $("#inputRegLastName").attr("placeholder", myLang.mdlInputLastName);
        $("#inputRegEmail").attr("placeholder", myLang.mdlInputMail);
        $("#inputRegPassword").attr("placeholder", myLang.mdlInputPass);
        $("#btnRegister").html(myLang.mdlBtn1);
        $(".btnMdlClose").html(myLang.mdlBtn2);

        // Page title
        document.title = myLang.pageTitle;

        // Links
        $(".lblChangepassword").html(myLang.lblChangepassword);
    });
}