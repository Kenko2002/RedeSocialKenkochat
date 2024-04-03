<?php 
session_start(); 

if (!isset($_SESSION['id_user'])) {
  header("Location: ./../login/index.php");
  exit(); 
}

//var_dump($_SESSION); 

?>


<head>
    <title>Perfil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- Corrected jQuery version -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <!-- select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="perfil.js"></script>
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
                





                <div style="border: 1px solid #FFF;border-radius:0.25rem;">
                  
                    <div class="card-header" style="background-color:#333;border-color:white">
                    <h5> Perfil </h5>
                    </div>
                    

                    <div class="card-body" style="background-color:#444;border: 1px solid #FFF;border-bottom-right-radius:0.25rem; border-bottom-left-radius:0.25rem; display: flex; align-items: center;">
                        <!-- Conteúdo do painel -->
                        <div style="border-color:white;width:35%">
                            <img id="pfp" style="border-radius:50%;margin:5%" src="https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg" width="200" height="200">
                        </div>
                        <div class="ml-3"style="height:100%;width:100%;">
                          <input disabled type="text" class="form-control" id="user_name" style="min-width:100%;background-color:#333;color:white;border-radius:0px; border-top-left-radius:0.25rem;border-top-right-radius:0.25rem;" value="<?php echo($_SESSION["user"]);?>">  </input>
                          <textarea type="text" class="custom-div form-control" id="user_bio" style="min-width:100%;min-height:25vh; height:25vh;background-color:#333;color:white;border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem;"></textarea>
                        </div>
                    </div>


                  <div class="card-footer" style="border-color:white;background-color:#333">
                      <!-- modal footer -->
                      <button class="btn btn-secondary" style=" background-color:#444;color:white;border-color:white"id="add_pessoa_amizade"> Adicionar Amigos </button>
                      <button class="btn btn-secondary" style=" background-color:#444;color:white;border-color:white"id="lista_de_amigos"> Lista de Amigos </button>
                      <button class="btn btn-secondary" style=" background-color:#444;color:white;border-color:white"id="requisicoes_de_amizade"> Pedidos de Amizade </button>
                      
                    </div>

                </div>
                
            </div>
        </div>
      </div>
















<!-- Modal adicionar pessoa amizade-->
<div class="modal fade" id="modal_addpessoa_amizade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Adicionar Amigos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <row class="row">
            <input type="text" id="input_nome_pessoa_amizade_pesquisar" class="form-control" style="border-radius:0px; border-top-left-radius:0.25rem;margin-left:5%;width:70%;background-color:#333;color:white;" placeholder="Digite o nome da pessoa">
            <button type="button" id="add_pessoa_amizade_pesquisar" class="btn btn-secondary" style="border-color:white;color:white;background-color:#444; border-bottom-left-radius:0px; border-bottom-radius:0px;border-bottom-right-radius:0px; border-top-left-radius:0px;">Pesquisar</button>
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
          <table  id="query-pessoas-adicionar-amizade">
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


<!-- Modal lista de amigos -->
<div class="modal fade" id="modal_lista_de_amigos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Lista de Amigos</h5>
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
        <div class="custom-div" style="border-color:white;background-color:#333;border-radius:0.25rem;">
          <table  id="query-pessoas-lista-amigos">
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


<!-- Modal requisições de amizade -->
<div class="modal fade" id="modal_requisicoes_de_amizade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Pedidos de Amizade</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->

        
        <div class="custom-div" style="border-color:white;background-color:#333;border-radius:0.25rem;">
        
          <table  id="query-requisicoes_de_amizade">
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


<!-- Modal Trocar foto-->
<div class="modal fade" id="trocar_foto_perfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="trocar_foto_perfil_title">Criar sala de Chat.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <input type="text" id="input_trocar_foto_perfil" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Digite o link da nova foto">
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id="trocar_foto_perfil_confirmar" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Trocar foto</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
      $('#pfp').click(function(){
        $('#trocar_foto_perfil').modal('show');
      });
    });

  $(document).ready(function(){
      $('#add_pessoa_amizade').click(function(){
        $('#modal_addpessoa_amizade').modal('show');
      });
  });
  $(document).ready(function(){
      $('#lista_de_amigos').click(function(){
        $('#modal_lista_de_amigos').modal('show');
      });
  });
  $(document).ready(function(){
      $('#requisicoes_de_amizade').click(function(){
        $('#modal_requisicoes_de_amizade').modal('show');
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
