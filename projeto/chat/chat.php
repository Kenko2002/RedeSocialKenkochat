<?php 
session_start(); 
//var_dump($_SESSION); 
if (!isset($_SESSION['id_user'])) {
  header("Location: ./../login/index.php");
  exit(); 
}

?>



<head>
    <title>Chat do Renzo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- Corrected jQuery version -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <script src="chat.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<iframe  src="./../notificacao/notificacao.php" frameborder="0" style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; width: 100%; height: 100%;"></iframe>

<body id="body" style="background-color: #192A56; background-color: #333; color:white" >

    <div class="container mt-5" >
        <div class="row justify-content-center">

        <!--coluna para Navegação-->
        <div class="col-md-3">
              <div class="card p-4 shadow" id="Box2" style="background-color:#444;height:100%;">
                  <div>
                      <div class="custom-div" style="background-color:#333;height:85vh">
                            <!--navegação conteúdo-->
                            <button class="btn btn-primary perfil" style="width:100%; align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-top:1%;">
                                Perfil 
                            </button>
                            <button class="btn btn-primary chats" style="width:100%;align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-top:1%;">
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


            <!-- First column for chat -->
            <div class="col-md-6">
                <div class="card p-4 shadow" id="Box2" style="background-color:#444">
                    
                    <button class="btn btn-sucess abrir_info_sala" style="color:white;border-color:white;border-bottom-left-radius:0px;border-bottom-right-radius:0px"> <h4 id="div_name">Chat Geral</h4> </button> 
                    

                    <div>
                        <div class="custom-div" id="chat-query" style="background-color:#333">
                            <!-- Chat content -->
                        </div>
                    </div>
                    
                    <div style="display:inline-block;padding:0px;margin:0px">
                      <button id="add_pessoa_chat"class="btn"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Adicionar Pessoa ao chat</button>
                      <button class="btn cls"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Limpar</button>
                      <button class="btn deleteroom"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Apagar Sala</button>
                      <button class="btn leaveroom"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Sair da sala</button>
                    </div>

                      <div class="input-group" style="border-top-radius: 0px;">
                        <input type="text" id="message" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem" placeholder="Digite sua Mensagem...">
                        <div class="input-group-append">
                            <button id="send" class="btn btn-success" style="height: 38px; background-color:#333; border-color:white; border-radius:0px;border-bottom-right-radius:0.25rem">
                                <img src="https://cdn-icons-png.flaticon.com/512/3106/3106794.png" style="height: 20px;">
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second column for chat rooms -->
            <div class="col-md-3">
                <div class="card p-4 shadow" id="Box2" style="background-color:#444; height:100%">
                    <h4 id="salas_name">Salas de Chat:</h4>
                    <div>
                        <div class="custom-div" id="salas-query" style="background-color:#333">
                            <!-- Chat room content -->
                        </div>
                    </div>
                
                    <button class="btn btn-primary" id="criar_sala" style="background-color:#333;border-color:white; border-radius:0px;border-bottom-radius:0.25rem"> Criar Sala </button>
                </div>
            </div>
            
        </div>
    </div>

<!-- Modal criar sala-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Criar sala de Chat.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <input type="text" id="input_room_name" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Digite o nome da sala">
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id="criar_sala_confirmar" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Criar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<!-- Modal adicionar pessoa-->
<div class="modal fade" id="modal_addpessoa_chat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Adicionar Pessoa ao chat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <row class="row">
            <input type="text" id="input_nome_pessoa_pesquisar" class="form-control" style="border-radius:0px; border-top-left-radius:0.25rem;margin-left:5%;width:70%;background-color:#333;color:white;" placeholder="Digite o nome da pessoa">
            <button type="button" id="add_pessoa_chat_pesquisar" class="btn btn-secondary" style="border-color:white;color:white;background-color:#444; border-bottom-left-radius:0px; border-bottom-radius:0px;border-bottom-right-radius:0px; border-top-left-radius:0px;">Pesquisar</button>
        </row>

        <style>
          table {
            width: 100%; /* Define a largura total da tabela */
            border-collapse: collapse; /* Mescla as bordas das células */
          }
          th, td {
            text-align: left; /* Alinha o texto à esquerda nas células */
          }
          th {
            background-color: #f2f2f2; /* Define uma cor de fundo para as células de cabeçalho */
          }
          td:first-child {
            width: 45%; /* Define a largura da primeira coluna */
          }
          td:nth-child(2) {
            width: 15%; /* Define a largura da segunda coluna */
          }
          
        </style>
        <div class="custom-div" style="border-color:white;background-color:#333;border-radius:0.25rem;">
          <table  id="query-pessoas-adicionar-ao-chat">
            <!--ajax content aqui-->
          </table>
        </div>

      </div>
      <div class="modal-footer"style="background-color:#333">
        <!-- <button type="button" id="add_pessoa_chat_confirmar" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Adicionar</button> -->
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<!--Modal #modal_info_sala-->
<div class="modal fade" id="modal_info_sala" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="InfosDaSalaCabeçalho">Informações da sala:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->

        <style>
          table {
            width: 100%; /* Define a largura total da tabela */
            border-collapse: collapse; /* Mescla as bordas das células */
          }
          th, td {
            text-align: left; /* Alinha o texto à esquerda nas células */
          }
          th {
            background-color: #f2f2f2; /* Define uma cor de fundo para as células de cabeçalho */
          }
          td:first-child {
            width: 45%; /* Define a largura da primeira coluna */
          }
          td:nth-child(2) {
            width: 15%; /* Define a largura da segunda coluna */
          }
          
        </style>

          <div id="player">
            <iframe width="100%" height="100%"  id="audioplayer" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
          </div>
        <div class="custom-div" style="border-color:white;background-color:#333;border-radius:0.25rem;height:50vh;">
          <table style="margin-left:5%;width:95%" id="query-informações-da-sala">
            <!--ajax content aqui-->
          </table>
        </div>

      </div>
      <div class="modal-footer"style="background-color:#333">
        <!-- <button type="button" id="add_pessoa_chat_confirmar" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Adicionar</button> -->
        <button type="button" class="btn btn-secondary" id="renomear_sala" style="background-color:#444" >Renomear</button>
        <button type="button" class="btn btn-secondary" id="mudar_musica_sala" style="background-color:#444" >Tema</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<!--Modal mudar-musica-->
<div class="modal fade" id="modal_mudar_musica" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Tema da sala:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->

        <input type="text" id="input_link_tema" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Digite o link de incorporação do novo tema da sala.">

      </div>
      <div class="modal-footer"style="background-color:#333">
        <!-- <button type="button" id="add_pessoa_chat_confirmar" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Adicionar</button> -->
        <button type="button" id="button_trocar_tema" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Confirmar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>


<!--Modal renomear sala -->
<div class="modal fade" id="modal_renomear_sala" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Renomear sala:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->

        <input type="text" id="input_renomear_sala" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Digite o novo nome da sala.">

      </div>
      <div class="modal-footer"style="background-color:#333">
        <!-- <button type="button" id="add_pessoa_chat_confirmar" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Adicionar</button> -->
        <button type="button" id="button_renomear_sala" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Confirmar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>





<!-- Your custom CSS -->
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

<script>
  // JavaScript to trigger the modal when the button is clicked
  $(document).ready(function(){
    $('#criar_sala').click(function(){
      $('#myModal').modal('show');
    });
  });

  $(document).ready(function(){
    $('#add_pessoa_chat').click(function(){
      $('#modal_addpessoa_chat').modal('show');
    });
  });

  
  $(document).ready(function(){
    $('.abrir_info_sala').click(function(){
      $('#modal_info_sala').modal('show');
    });
  });

  $(document).ready(function(){
    $('#mudar_musica_sala').click(function(){
      $('#modal_mudar_musica').modal('show');
    });
  });

  $(document).ready(function(){
    $('#renomear_sala').click(function(){
      $('#modal_renomear_sala').modal('show');
    });
  });


  

</script>

</body>
</html>