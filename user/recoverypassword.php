<?php
    session_start();
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<div class="container">
    <div class="container col-xxl-8 px-4 py-5">
        <div class="row">
            <div class="col-12">
            	<form class="needs-validation-restore" novalidate>
	                <div class="mb-3 position-relative">
						<label for="inputNewPassword" class="form-label">Please enter your new password</label>
						<input type="password" style="width:200px;" class="form-control" id="inputNewPassword" required>
						<div class="invalid-tooltip">
							This required
						</div>
					</div>
					<div class="mb-3 position-relative">
						<label for="inputConfirmPassword" class="form-label">Please confirm your new password</label>
						<input type="password" style="width:200px;" class="form-control" id="inputConfirmPassword" required>
						<div class="invalid-tooltip lableVerify">
							This required
						</div>
					</div>

					<button type="button" class="btn btn-success mb-3" id="btnRestore">Change my password</button>
				</form>

				<form class="needs-validation-sendlink d-none" novalidate>
	                <div class="mb-3 position-relative">
						<label for="inputNewPassword" class="form-label">
							The password recovery link has expired, you must generate a new link
						</label>
						<input style="width:200px;" type="text" class="form-control" id="inputRestoreMail" name="inputRestoreMail" aria-describedby="emailHelp" placeholder="Email to restore" required>
						<div class="invalid-tooltip">
							This required
						</div>
					</div>

					<button type="button" class="btn btn-success" id="btnSendlink">Send me a new link</button>
				</form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var userId = <?php echo (array_key_exists('restore', $_GET)) ? $_GET['restore'] : 0; ?>;
    	token  = "<?php echo (array_key_exists('token', $_GET)) ? $_GET['token'] : ''; ?>";

    $(document).ready(function(){
    	// Validar que sea un id valido
    	if(userId == 0)
    		$("#btnRestore").prop("disabled", "disabled");

    	$("#btnRestore").click( fnResotre);

    	$("#btnSendlink").click( fnRestorePassword);
    });

    function fnResotre(){
    	let forms = document.querySelectorAll('.needs-validation-restore'),
            continuar = true;

        Array.prototype.slice.call(forms).forEach(function (form){ 
                if (!form.checkValidity())
                    continuar = false;

                form.classList.add('was-validated');
        });

        if(!continuar)
        	return false;

        if( $("#inputNewPassword").val() != $("#inputConfirmPassword").val() ){
        	$(".needs-validation-restore").removeClass("was-validated");
        	$("#inputConfirmPassword").addClass("is-invalid");
        	$(".lableVerify").html("Passwords do not match");
        	return false;
        }

        let objData = {
        	"_method": "updatePassword",
        	"newPassword": $("#inputConfirmPassword").val(),
        	"userId": userId
        };

        $.ajax({
            url: `${base_url}/core/controllers/user.php`,
            data: objData,
            type: 'POST',
            dataType: 'json',
            headers: {
                "Authorization": `Bearer ${token}`
            },
            success: function(response){
                if(response.codeResponse == 200){
                	window.location.replace("../index.html");
                }else{
                	$(".needs-validation-restore").addClass("d-none");
                	$(".needs-validation-sendlink").removeClass("d-none");
                }
            }
        });
    }

    function fnRestorePassword(){
            let forms = document.querySelectorAll('.needs-validation-sendlink'),
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

            $.post(`${base_url}/core/controllers/user.php`, objData, function(result) {
                if(result.data.existe == 0){
                    Swal.fire({
                        position: 'top-end',
                        icon: "warning",
                        text: "The user is not registered",
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
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