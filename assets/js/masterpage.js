// Se comparte la variable gobal de raiz para cargar contenido de lado cliente
var searchRequest = null,
    lang = (window.navigator.language).substring(0,2);

$(document).ready(function(){
    // Metodo para el cierre de session
    $("#btnLogout").on("click", function(){
        localStorage.removeItem("logged");
        window.location.replace(`${base_url}/account/logout.php`);
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