<!DOCTYPE html>
<html lang="en">
    
<style>
  @font-face {
    font-family: Minecraft;
    src: url('./minecraft_font.ttf') format('truetype');
}

body {
    font-family: Minecraft, Arial, sans-serif; /* Use sua fonte como primeira escolha e depois fontes de fallback */
}
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">
</head>
<body style="background-color: #333; color:white">
    <div class="container" style="margin-top:15%;">
        <center>
            <!-- <img src="https://images.vexels.com/media/users/3/155736/isolated/preview/1a7374be2abfacd66ffe0df0523a4085-icone-de-estilo-de-linha-de-ovos-de-galinha.png" style="max-width: 200px; max-height: 200px;"> -->
        </center>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading" style="background-color: #444;color:white">
                        <!-- <h3 class="panel-title">Sistema Pessoal de Galinhagem - Login</h3> -->
                        <h3 class="panel-title">Login</h3>
                    </div>
                    <div class="panel-body" style="background-color: #444">
                                <label for="input1">Usuário:</label>
                                <input type="text" class="form-control" style="background-color: #222; color:white" id="input1" placeholder="Digite seu usuário">
                                <label for="input2">Senha:</label>
                                <input type="password" class="form-control" style="background-color: #222; color:white" id="input2" placeholder="Digite sua senha">
                            <button class="btn btn-primary btn-block" style="margin-top: 10px;background-color: #222;" id="login">Login</button>
                            <button class="btn btn-success btn-block" style="background-color: #222;" id="cadastrar">Cadastrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="./login.js"></script>
</body>
</html>
