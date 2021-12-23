<?php
    session_start();
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<div class="container">
    <div class="container col-xxl-8 px-4 py-5">
        <div class="row">
            <div class="col-lg-4">
            	<form class="needs-validation-restore" novalidate>
	                <div class="mb-3">
						<label for="inputNewPassword" class="form-label">Please enter your new password</label>
						<input type="password" class="form-control" id="inputNewPassword" required>
					</div>
					<div class="mb-3">
						<label for="inputConfirmPassword" class="form-label">Please confirm your new password</label>
						<input type="text" class="form-control" id="inputConfirmPassword" required>
					</div>

					<button type="button" class="btn btn-success mb-3" id="btnRestore">Change my password</button>
				</form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" ></script>
<script type="text/javascript">
    var userId = <?php echo (array_key_exists('restore', $_GET)) ? $_GET['restore'] : 0; ?>;

    $(document).ready(function(){
    	// Validar que sea un id valido
    	if(userId == 0)
    		$("#btnRestore").prop("disabled", "disabled");

    	$("#btnRestore").click( fnResotre);
    });

    function fnResotre(){
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

        if( $("#inputNewPassword").val() != $("#inputConfirmPassword").val() ){
        	return false;
        }


    }

    function changePageLang(language) {
        let myLang = language["userPage"];
    }
</script>

<?php
    // Se obtiene el contenido del bufer
    $content = ob_get_contents();

    // Limpiar el bufer para liberar
    ob_end_clean();

    // Se carga la pagina maestra para imprimir la pagina global
    include("../masterPage.php");
?>