<?php 
session_start(); 
include "./../api/conexao.php";
if (!isset($_SESSION['id_user'])) {
  header("Location: ./../login/index.php");
  exit(); 
}

//var_dump($_SESSION); 

?>


<head>
    <title>Feeding</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- Corrected jQuery version -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <script src="feeding.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<iframe src="./../notificacao/notificacao.php" frameborder="0" style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; width: 100%; height: 100%;"></iframe>
    
<body id="body" style="background-color: #192A56; width:99%;background-color: #333; color:white" >

    <div class="row justify-content-center">

        <!--coluna para Navegação-->
        <div class="col-md-3 mt-5  mb-2">
              <div class="card p-4 shadow" id="Box2" style="background-color:#444; height:100%">
                  <div>
                      <div class="custom-div" style="background-color:#333;height:85vh">
                            <!--navegação conteúdo-->
                            <button class="btn btn-primary perfil" style="width:100%; align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-top:1%;">
                                Perfil 
                            </button>
                            <button class="btn btn-primary chats" style="width:100%; align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-top:1%;">
                                Chats
                            </button>
                            <button class="btn btn-primary feeding" style="width:100%;align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-top:1%;">
                                Feeding
                            </button>
                            <button class="btn btn-primary tabuleiro" style="width:100%;align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-top:1%;">
                                Tabuleiro
                            </button>
                      </div>
                  </div>
              </div>
        </div>


        <div class="col-md-6 mt-5  mb-2">
          
            <div class="card p-4 shadow" style="border-radius:0.25rem; background-color:#444;">
                
                    <div style="border-color:white;background-color:#333; border: 1px solid #FFF">
                        <button class="btn btn-primary feeding-amigos" style="width:49.6%; height: 38px; background-color:#444; border-width:0px ; border-radius:0.25rem;margin:0px;">
                            Amigos
                        </button>
                        <button class="btn btn-primary feeding-geral" style="width:49.6%; height: 38px; background-color:#444; border-width:0px ; border-radius:0.25rem;margin:0px;">
                            Geral
                        </button>
                    </div>
                
                <div id="div-query-feeding" class="custom-div"style="background-color:#333;border: 1px solid #FFF;border-radius:0.25rem; height:75vh;border-top-right-radius:0rem;border-top-left-radius:0rem">
                    
                    <!--QUERY FEEDING-->
                        
                </div>

                <div style="border-color:white;background-color:#333; border: 1px solid #FFF">
                        <button class="btn btn-primary feeding-minhas-postagens" style="width:49.6%; height: 38px; background-color:#444; border-width:0px ; border-radius:0.25rem;margin:0px;">
                            Minhas Postagens
                        </button>
                        <button class="btn btn-primary feeding-nova-postagem" style="width:49.6%; height: 38px; background-color:#444; border-width:0px ; border-radius:0.25rem;margin:0px;">
                            Nova Postagem
                        </button>
                        
                </div>
            
            </div>
        </div>
      </div>











<!-- Modal nova postagem-->
<div class="modal fade" id="modal_feeding_nova_postagem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="modal_feeding_nova_postagem_title">Nova Postagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <textarea type="text" class="custom-div form-control" id="input1_feeding_nova_postagem" style="min-width:100%;min-height:25vh; height:25vh;background-color:#333;color:white;border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem;"placeholder="No que você está pensando?"></textarea>
        <input type="text" id="input2_feeding_nova_postagem" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Caso desejar adicionar uma imagem, cole o link dela aqui.">
        </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id="feeding_nova_postagem_confirmar" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Confirmar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<!-- Modal Comentar em postagem-->
<div class="modal fade" id="modal_comentarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title">Comentários</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="background-color:#444">

          <div class="row">
            <div  id="query-comentarios" class="custom-div"style="background-color:#333;margin-left:2%;width:70%;height:70vh;border:1px solid white">
            <!-- Modal content goes here -->
            </div>

            
            <div style="height:70vh;display: flex; flex-direction: column; justify-content: flex-end;">
              <img id="foto_user_comments" style="width:14vw;height:20vh;border-color:white;border: 1px solid white" src="https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg">
              <textarea type="text" class="custom-div form-control" id="input1_comentario" style="width:100%;min-height:45vh;max-height:45vh; height:25vh;background-color:#333;color:white;border-radius:0px;" placeholder="No que você está pensando?"></textarea>
              <input type="text" id="input2_comentario" class="form-control" style="background-color:#333;color:white; border-radius:0px;" placeholder="Link de imagem">
            </div>
          </div>
      
      </div>
      <div class="modal-footer" style="background-color:#333">
        <button type="button" id="comentario_confirmar" class="btn btn-secondary" style="background-color:#444" >Confirmar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>



<script>
  $(document).ready(function(){
      $('.feeding-nova-postagem').click(function(){
        $('#modal_feeding_nova_postagem').modal('show');
      });
  });
</script>

    </body>

<style>
    /* Estilo personalizado para a div */
    .custom-div {
        width: 100%;
        height: 70vh; /* 40% da altura da tela */
        overflow-y: scroll; /* Adicionar barra de rolagem vertical quando necessário */
        border: 1px solid #ccc;
        padding: 10px;
    }
    .custom-div::-webkit-scrollbar {
        width: 10px; /* Largura da barra de rolagem */
    }
    .custom-div::-webkit-scrollbar-thumb {
        background-color: #444; /* Cor da barra de rolagem */
    }
</style>

</html>
