<?php 
session_start(); 
var_dump($_SESSION); 
?>


<head>
    <title>Perfil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- Corrected jQuery version -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <script src="perfil.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

    <body style="background-color: #192A56; background-color: #333; color:white" >

        <div class="col-md-6 mx-auto mt-5">
            <div class="card p-4 shadow" style="border-radius:0.25rem; background-color:#444;">
                
                <div style="border: 1px solid #FFF;border-radius:0.25rem;">
                  
                    <div class="card-header" style="background-color:#333;border-color:white">
                    <h5> Perfil - <?php echo $_SESSION["user"] ?> </h5>
                    </div>
                    

                    <div class="card-body" style="background-color:#444;border: 1px solid #FFF;border-bottom-right-radius:0.25rem; border-bottom-left-radius:0.25rem; display: flex; align-items: center;">
                        <!-- Conteúdo do painel -->
                        <div style="border-color:white;width:35%">
                            <img id="pfp" style="border-radius:50%;margin:5%" src="https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg" width="200" height="200">
                        </div>
                        <div style="display:grid; align-items: center; flex-grow: 1;">
                          <input type="text" class="form-control" style="background-color:#333;color:white;border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem;"></input>
                          <input type="text" class="form-control" style="background-color:#333;color:white;border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem;"></input>
                          <input type="text" class="form-control" style="background-color:#333;color:white;border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem;"></input>
                          <input type="text" class="form-control" style="background-color:#333;color:white;border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem;"></input>
                          </div>
                    </div>


                  <div class="card-footer" style="border-color:white;background-color:#333">
                      
                  </div>

                </div>
                
            </div>
        </div>

















<!-- Modal criar sala-->
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
</script>

    </body>

</html>
