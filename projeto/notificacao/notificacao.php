<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu do Sino</title>
  <link rel="stylesheet" href="styles.css">
  <script src="./notificacao.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<style>
/* Estilo personalizado para a div */
.custom-div2 {
  overflow-y: scroll; /* Adicionar barra de rolagem vertical quando necessário */
    border: 0px solid #ccc;
  }
  .custom-div2::-webkit-scrollbar {
    width: 10px; /* Largura da barra de rolagem */
  }
  .custom-div2::-webkit-scrollbar-thumb {
    background-color: #333; /* Cor da barra de rolagem */
  }
</style>

<!-- Elemento HTML do menu -->
<div id="menu" style="max-height:50vh; max-width:10vw; width:10vw; height:40vh;" class="custom-div2">
  <!-- query notificacoes -->
</div>

<!-- Elemento HTML do sino -->


<div id="sino" >
  <center>
  <svg xmlns="http://www.w3.org/2000/svg" style="margin-top:7%;" width="80%" height="80%" fill="white" class="bi bi-bell" viewBox="0 0 16 16">
    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
  </svg>
  </center>
  <label id="num_notificacoes"style="padding-right:5px;padding-left:5px;border: 1px solid white; border-radius:50%;color:White">0</label>
  
</div>





</body>
</html>
