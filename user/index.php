<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap, Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

        <title>User page | Clubtres</title>
    </head>
<body>
    <div class="container">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="../assets/img/user/default.jpg" class="d-block mx-lg-auto img-fluid shadow" id="userPhoto" alt="User picture" width="700" height="500" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="fw-bold lh-1 mb-0" id="userName">Cuauhtemoc Hernandez</h1>
                    <p class="h1 mt-0" id="userId">#39849</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" id="userStatus"><b>Status</b> </li>
                        <li class="list-group-item" id="userMail"><b>Email</b> </li>
                        <li class="list-group-item" id="userAge"><b>Member since:</b> </li>
                        <li class="list-group-item" id="userTeams"><b>Teams member:</b> </li>
                        <li class="list-group-item" id="userPlaying"><b>playing in league:</b> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script type="text/javascript">
        var uri = "<?php echo $_SERVER[REQUEST_URI]; ?>";
        var host = "<?php $_SERVER[HTTP_HOST] ?>";
        console.log(`uri ${uri}`);
        console.log(`host ${host}`);
    </script>
</body>
</html>