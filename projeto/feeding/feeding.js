var id_postagem="";
var amigos_ou_geral="geral";

window.addEventListener('message', function(event) {
    if (event.data.action) {
        window.location.href =event.data.action;
    }

  });
  
$(document).on("click", ".perfil", function(){
    window.location.href = "./../perfil/perfil.php";
});
$(document).on("click", ".chats", function(){
    window.location.href = "./../chat/chat.php";
});
$(document).on("click", ".feeding", function(){
    window.location.href = "./../feeding/feeding.php";
});





$(document).on("click", "#feeding_nova_postagem_confirmar", function(){
    
    $.ajax({
        type: 'POST',
        url: './../api/feeding.api.php',
        data: {
            criar_nova_postagem: 'S',
            texto:$("#input1_feeding_nova_postagem").val(),
            imagem:$("#input2_feeding_nova_postagem").val()
        },
        dataType: 'text',
        success: function(data) {
            $("#input1_feeding_nova_postagem").val("");
            $("#input2_feeding_nova_postagem").val("");
            toastr.success('Postagem Criada.', 'Sucesso!');
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });

});


$(document).on("click", ".feeding-minhas-postagens", function(){
    amigos_ou_geral="minhas";
  callMinhasPostagens();
});
$(document).on("click", ".feeding-amigos", function(){
    amigos_ou_geral="amigos";
  callPostagensAmigos();
});
$(document).on("click", ".feeding-geral", function(){
    amigos_ou_geral="geral";
    callPostagensGeral();
});
$(document).on("click", ".tabuleiro", function(){
    window.location.href = "./../tabuleiro/tabuleiro.php";
});

  

function callMinhasPostagens(){
    $.ajax({
        type: 'POST',
        url: './../api/feeding.api.php',
        data: {
            feeding_minhas_postagens:'S'
        },
        dataType: 'html',
        success: function(data) {
            $("#div-query-feeding").empty().html(data);
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
}

function callPostagensAmigos(){
    $.ajax({
        type: 'POST',
        url: './../api/feeding.api.php',
        data: {
            feeding_amigos:'S'
        },
        dataType: 'html',
        success: function(data) {
            $("#div-query-feeding").empty().html(data);
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
}

function callPostagensGeral(){
    $.ajax({
        type: 'POST',
        url: './../api/feeding.api.php',
        data: {
            feeding_geral:'S'
        },
        dataType: 'html',
        success: function(data) {
            $("#div-query-feeding").empty().html(data);
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
}


$(document).on("click", "#comentario_confirmar", function(){
    
    $.ajax({
        type: 'POST',
        url: './../api/feeding.api.php',
        data: {
            novo_comentario:'S',
            id_postagem:id_postagem,
            texto:$("#input1_comentario").val(),
            imagem:$("#input2_comentario").val()
        },
        dataType: 'html',
        success: function(data) {
            $.ajax({
                type: 'POST',
                url: './../api/feeding.api.php',
                data: {
                    query_comentarios:'S',
                    id_postagem:id_postagem
                },
                dataType: 'html',
                success: function(data) {
                    $("#input1_comentario").val("");
                    $("#input2_comentario").val("");
                    $("#query-comentarios").empty().html(data);
                    if(amigos_ou_geral=="geral"){callPostagensGeral();
                    }
                    if(amigos_ou_geral=="amigos"){callPostagensAmigos();
                    }
                    if(amigos_ou_geral=="minhas"){callMinhasPostagens();
                    }
                    
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

$(document).on("click", ".comentarios", function(){
    id_postagem=$(this).attr('id').substring(12);
    $('#modal_comentarios').modal('show');
    
    $.ajax({
        type: 'POST',
        url: './../api/feeding.api.php',
        data: {
            query_comentarios:'S',
            id_postagem:id_postagem
        },
        dataType: 'html',
        success: function(data) {
            $("#query-comentarios").empty().html(data);
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });



});

$(document).on("click", ".compartilhar", function(){
    var id_postagem=$(this).attr('id').substring(13);
    $(this).prop('disabled', true);
    $("#svg_compartilhar_"+id_postagem).css('fill', 'limegreen');

    $.ajax({
        type: 'POST',
        url: './../api/feeding.api.php',
        data: {
            compartilhar:'S',
            id_postagem:id_postagem
        },
        dataType: 'html',
        success: function(data) {
            toastr.success('Postagem compartilhada!', 'Sucesso!');
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });

});


function callfotousuario(){
    $.ajax({
        type: 'POST',
        url: './../api/perfil.api.php',
        data: {
            get_user_picture:"s"
        },
        dataType: 'json', 
        success: function(data) {
            $("#foto_user_comments").attr("src",data.link_foto);
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
};

$(document).ready(function() {

    callPostagensGeral();
    callfotousuario();
   

    


    //feeding-amigos
    //feeding-geral
    
});