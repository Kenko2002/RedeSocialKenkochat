<?php 
session_start(); 

if (!isset($_SESSION['id_user'])) {
  header("Location: ./../login/index.php");
  exit(); 
}



// var_dump($_SESSION); 

?>


<head>
    <title>Tabuleiro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="boardstyle.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- Corrected jQuery version -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


    <script src="./../notificacao/notificacao.js"></script>
    <script src="tabuleiro.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../notificacao/styles.css">
</head>
<style>
    .popover-body {
      background-color: #333;
      border: 1px solid white;
    }
  </style>

<body id="body" style="background-color: #192A56; width:99%;background-color: #333; color:white" >
   




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
              <div id="menu" style="z-index:9999; max-height:50vh; max-width:10vw; width:10vw; height:40vh;" class="custom-div2">
                <!-- query notificacoes -->
              </div>

              <!-- Elemento HTML do sino -->
              <div id="sino" style="z-index:9999;">
                <center>
                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top:7%;" width="80%" height="80%" fill="white" class="bi bi-bell" viewBox="0 0 16 16">
                  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                </svg>
                </center>
                <label id="num_notificacoes"style="padding-top:0px;padding-bottom:0px;padding-right:5px;padding-left:5px;border: 1px solid white; border-radius:50%;color:White">0</label>
                
              </div>






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



        <div class="col-md-6 mt-5  mb-2" >
          
            <div class="card p-4 shadow" style="height:92vh;border-radius:0.25rem; background-color:#444;">
                

                </head>
                    <body>

                        <button id="tabuleiro_info" onclick="abrirModalTabuleiroInfo()" class="btn btn-primary" style="width:100%; align-self: flex-end; height: 38px; background-color:#333; border-color:white;border-width:1px ; border-radius:0px; margin-top:1%;" >Atualizar Tabuleiro</button>

                    
                        <div id="board_container" class="board_container scrollable" style="background-color:#333;height:100%;">
                            <div id="board" class="board"></div>
                        </div>
                        <div class="row" style="margin-left:0.03vw;">
                          <button class="btn delete_tabuleiro"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Apagar Tabuleiro</button>
                          <button class="btn leave_tabuleiro"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333;padding-left:7px;padding-right:7px;">  Sair  </button>
                          <button class="btn add_pessoa_tabuleiro"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Adicionar pessoa</button>
                          <button class="btn add_token_tabuleiro"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Novo Token</button>
                          <button class="btn add_area_magia"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Área de Efeito</button>
                          <button class="btn button_fichas"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Fichas</button>
                          <button class="btn recursos-button"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Recursos</button>
                          <button class="btn notas-button"style="border-color:white;padding:2px;border-radius:0px;margin:0px;color:white;background-color:#333">Notas</button>
                        </button>
                        
                        </div>
                    </body>
                </html>


            </div>

        </div>

                <!--segunda coluna para tabuleiros-->
                <div class="col-md-3 mt-5  mb-2">
                    <div class="card p-4 shadow" id="Box2" style="background-color:#444; height:92vh">
                        <h4 id="tabuleiros_name">Tabuleiros</h4>
                        <div>
                            <div class="custom-div" id="seletor-tabuleiros-query" style="background-color:#333;height:75vh;">
                                <!-- Chat room content -->
                            </div>
                        </div>
                    
                        <button class="btn btn-primary" id="criar_tabuleiro" style="background-color:#333;border-color:white; border-radius:0px;border-bottom-radius:0.25rem"> Criar Tabuleiro </button>
                    </div>
                </div>

      </div>





<!-- Modal editar infos tabuleiro-->
<div class="modal fade" id="mudar_tabuleiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="mudar_tabuleiro_title">Configurar Tabuleiro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
      
        <!-- Modal content goes here -->
                    <div class="row">
                        <input type="number" id="cols" value="" min="1" class="form-control" style="width:50%;background-color:#333;color:white;border-radius:0px; border-top-left-radius:0.25rem;border-top-right-radius:0.25rem;"placeholder="Colunas">
                        <input type="number" id="rows" value="" min="1" class="form-control" style="width:50%;background-color:#333;color:white;border-radius:0px; border-top-left-radius:0.25rem;border-top-right-radius:0.25rem;"placeholder="Linhas">
                    </div>
                    <div class="row">
                        <input type="text" id="background" value="" class="form-control" style="width:100%;background-color:#333;color:white;border-radius:0px; border-top-left-radius:0.25rem;border-top-right-radius:0.25rem;"placeholder="URL da imagem de fundo">
                    </div>

                    <div style="margin-left:3%;">
                    <input class="form-check-input" type="checkbox" value="" id="check_mostrar_grid" checked>
                    <label class="form-check-label">Mostrar Grid</label>
                    </div>

      </div>

      <div class="modal-footer"style="background-color:#333">
      <button onclick="updateBoardInfo()" id="button_atualizar_tabuleiro" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Atualizar Tabuleiro</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<!-- Modal criar tabuleiro-->
<div class="modal fade" id="modal_criar_tabuleiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Criar tabuleiro.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <input type="text" id="input_tabuleiro_name" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Digite o nome do tabuleiro">
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id="criar_tabuleiro_confirmar" class="btn btn-secondary criar_tabuleiro_conf" style="background-color:#444" data-dismiss="modal">Criar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>




<!-- Modal adicionar pessoa-->
<div class="modal fade" id="modal_addpessoa_tabuleiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Adicionar Pessoa ao Tabuleiro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <row class="row">
            <input type="text" id="input_nome_pessoa_pesquisar" class="form-control" style="border-radius:0px; border-top-left-radius:0.25rem;margin-left:5%;width:70%;background-color:#333;color:white;" placeholder="Digite o nome da pessoa">
            <button type="button" id="add_pessoa_tabuleiro_pesquisar" class="btn btn-secondary" style="border-color:white;color:white;background-color:#444; border-bottom-left-radius:0px; border-bottom-radius:0px;border-bottom-right-radius:0px; border-top-left-radius:0px;">Pesquisar</button>
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
          <table  id="query-pessoas-adicionar-ao-tabuleiro">
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

<!-- Modal add token-->
<div class="modal fade" id="modal_add_token_tabuleiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Adicionar Token ao Tabuleiro.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <input type="text" id="link_imagem_add_token" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="URL da imagem do token">
      
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id="criar_token_confirmar" class="btn btn-secondary criar_tabuleiro_conf" style="background-color:#444" data-dismiss="modal">Criar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<!-- Modal add area de magia-->
<div class="modal fade" id="modal_add_area_magia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Adicionar área de efeito.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <div class="row">
          <input type="number" id="altura_area_efeito" class="form-control" style="margin-left:1%;width:49%;background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Altura">
          <input type="number" id="largura_area_efeito" class="form-control" style="width:49%;background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Largura">
        </div>
        <div class="row">
          <input type="number" id="x_area_efeito" class="form-control" style="margin-left:1%;width:49%;background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Posição X">
          <input type="number" id="y_area_efeito" class="form-control" style="width:49%;background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Posição Y">
        </div>
        <input type="text" class="form-control" id="colorPicker_criar_area" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Insira uma cor" readonly>
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id="criar_area_efeito_confirmar" class="btn btn-secondary criar_tabuleiro_conf" style="background-color:#444" data-dismiss="modal">Criar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<!-- Modal modal_area_effect-->
<div class="modal fade" id="modal_area_effect" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Editar área de efeito</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <div class="row">
          <input type="number" id="editar_altura_area_efeito" class="form-control" style="margin-left:1%;width:49%;background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Altura">
          <input type="number" id="editar_largura_area_efeito" class="form-control" style="width:49%;background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Largura">
        </div>
        <input type="text" class="form-control" id="colorPicker_editar_area" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Insira uma cor" readonly>
      </div>
      <div class="modal-footer"style="background-color:#333">
      <button type="button" id="excluir_area_efeito_confirmar" class="btn btn-secondary" style="background-color:#444;border-color:red;color:red;margin-right:8.75vw;" data-dismiss="modal">Excluir área de Efeito</button>
        <button type="button" id="editar_area_efeito_confirmar" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Criar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>


<!-- Modal modal_token_click-->
<div class="modal fade" id="modal_token_click" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:9998;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Editar Token</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        <input type="text" id="input_token_url_edit" class="form-control" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem" placeholder="Url da imagem">
        
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id="excluir_token_click_confirmar" class="btn btn-secondary" style="background-color:#444;border-color:red;color:red;margin-right:3.75vw;"  data-dismiss="modal">Excluir Token</button>
        <button type="button" id="token_click_duplicar" class="btn btn-secondary" style="background-color:#444"  data-dismiss="modal">Duplicar</button>
        <button type="button" id="token_click_confirmar" class="btn btn-success" style="background-color:#444"  data-dismiss="modal">Confirmar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>


<!-- Modal modal_recursos-->
<div class="modal fade" id="modal_recursos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Recursos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="query-recursos" class="modal-body"style="background-color:#444">
        <!-- Modal content goes here -->
        
        
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<style>
    .modal-dialog2 {
        width: 80% !important;
        max-width: 80% !important;
    }
</style>

<!-- Modal modal_fichas-->
<div class="modal fade" id="modal_fichas"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-dialog2" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Fichas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="query-fichas" class="modal-body"style="background-color:#444;margin:0;padding:0;">
        <!-- Modal content goes here -->
        
        
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id_ficha="0" id="botao_editar_ficha"class="btn btn-success" style="background-color:#444;display:none" >Editar Ficha</button>
        <button type="button" id_ficha="0" id="botao_salvar_ficha"class="btn btn-success" style="background-color:#444;display:none" >Salvar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>

<!-- Modal modal_notas-->
<div class="modal fade" id="modal_notas"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-dialog2" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#333">
        <h5 class="modal-title" id="exampleModalLabel">Notas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="query-notas" class="modal-body"style="background-color:#444;margin:0;padding:0;">
        <!-- Modal content goes here -->
        
        
      </div>
      <div class="modal-footer"style="background-color:#333">
        <button type="button" id_nota="0" id="botao_excluir_nota"class="btn btn-danger" style="color:red;background-color:#444;display:none" >Excluir Nota</button>
        <button type="button" id_nota="0" id="botao_adicionar_nota"class="btn btn-success" style="background-color:#444;float:left;display:none" >Adicionar Nota</button>
        <button type="button" id_nota="0" id="botao_editar_nota"class="btn btn-success" style="background-color:#444;display:none" >Editar Nota</button>
        <button type="button" id_nota="0" id="botao_salvar_nota"class="btn btn-success" style="background-color:#444;display:none" >Salvar</button>
        <button type="button" class="btn btn-secondary" style="background-color:#444" data-dismiss="modal">Cancelar</button>
        <!-- You can add additional buttons here if needed -->
      </div>
    </div>
  </div>
</div>



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

<script>
  $(document).ready(function(){
    $('#colorPicker_criar_area').colorpicker();
  });
  $(document).ready(function(){
    $('#colorPicker_editar_area').colorpicker();
  });

$(document).ready(function(){
    $('#criar_tabuleiro').click(function(){
      $('#modal_criar_tabuleiro').modal('show');
    });
  });


$(document).ready(function(){
  $(document).on("click", ".button_fichas", function(){

  $.ajax({
        type: 'GET',
        url: './../api/tabuleiro.api.php',
        data: {
            query_modal_fichas:'S',
            id_tabuleiro:id_tabuleiro_atual
        },
        dataType: 'html',
        success: function(data) {
            $("#query-fichas").empty().append(data);
            
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        } 
      });
  $('#modal_fichas').modal('show');
  });

});




$(document).ready(function(){
    $('.notas-button').click(function(){
      $("#botao_adicionar_nota").css("display","block");
      $("#botao_editar_nota").css("display","none");
      $("#botao_salvar_nota").css("display","none");
      $('#modal_notas').modal('show');
    
    });
  });

  $(document).ready(function(){
    $('.add_pessoa_tabuleiro').click(function(){
      $('#modal_addpessoa_tabuleiro').modal('show');
    });
  });

  $(document).ready(function(){
    $('.add_token_tabuleiro').click(function(){
      $('#modal_add_token_tabuleiro').modal('show');
    });
  });
  
  $(document).ready(function(){
    $('.add_area_magia').click(function(){
      $('#modal_add_area_magia').modal('show');
    });
  });

  $('.token').click(function(){
    id_token=$(event.target).attr("id").substring(6);
          $.ajax({
            type: 'GET',
            url: './../api/tabuleiro.api.php',
            data: {
                get_token_image:'S',
                id_token:id_token
            },
            dataType: 'json',
            success: function(data) {
                $("#input_token_url_edit").val(data[0]["link_foto"]);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            } 
          });
      });

  
  $(document).on("click", function(event) {
    if($(event.target).hasClass("area_effect")){
        id_area_effect=$(event.target).attr("id_effect_area");

        $("#editar_altura_area_efeito").val( $(event.target).attr("altura") );
        $("#editar_largura_area_efeito").val( $(event.target).attr("largura") );

        $('#modal_area_effect').modal('show');
    }if($(event.target).hasClass("token")){
        id_token=$(event.target).attr("id").substring(6);
        $.ajax({
          type: 'GET',
          url: './../api/tabuleiro.api.php',
          data: {
              get_token_image:'S',
              id_token:id_token
          },
          dataType: 'json',
          success: function(data) {
              $("#input_token_url_edit").val(data[0]["link_foto"]);
          },
          error: function(xhr, status, error) {
              console.log("Erro na solicitação AJAX: " + status + " - " + error);
          } 
        });

        $('#modal_token_click').modal('show');
      }

  



  
  $('#botao_salvar_ficha').on('click', function() {
    $("#botao_salvar_ficha").hide();
    $("#botao_editar_ficha").show();
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            salvar_ficha:'S',
            id_ficha:$("#div_ficha").attr("id_ficha"),
            id_token:$("#div_ficha").attr("id_token"),
            texto:$("#textbox_edicao_ficha").val()
        },
        dataType: 'text',
        success: function(data) {
          //toastr.success("Ficha salva com sucesso!","Sucesso!");
          
                      $.ajax({
                        type: 'POST',
                        url: './../api/tabuleiro.api.php',
                        data: {
                            get_ficha_by_token_id: 'S',
                            id_token: $("#div_ficha").attr("id_token")
                        }, // Corrigindo a vírgula aqui
                        dataType: 'html',
                        success: function(data) {
                            $("#div_ficha").empty().append(data);
                          },
                        error: function(xhr, status, error) {
                            console.log("Erro na solicitação AJAX: " + status + " - " + error);
                        } 
                    });
                      

        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});

$('#botao_editar_ficha').on('click', function() {
    $("#botao_editar_ficha").hide();
    $("#botao_salvar_ficha").show();
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            get_textbox_ficha:'S',
            id_ficha:$("#div_ficha").attr("id_ficha")
        },
        dataType: 'html',
        success: function(data) {
            $("#div_ficha").empty().append(data);
            
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});

  });
</script>

</html>




