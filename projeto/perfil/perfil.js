window.addEventListener('message', function(event) {
    if (event.data.action) {
        window.location.href =event.data.action;
    }

  });
  



function callUserId(){
    $.ajax({
        type: 'GET',
        url: './../api/perfil.api.php',
        data: {
            get_user_id: 'S'
        },
        dataType: 'json', 
        success: function(data) {
            var user_id = data.id_user;
            //console.log("ID do usuário: " + user_id);
            $("#trocar_foto_perfil_confirmar").val(user_id);
            return user_id;
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
}

function callfotousuario(){
    $.ajax({
        type: 'POST',
        url: './../api/perfil.api.php',
        data: {
            get_user_picture:"s"
        },
        dataType: 'json', 
        success: function(data) {
            $("#pfp").attr("src",data.link_foto);
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
};

$(document).ready(function() {

    var user_id=callUserId();

    callfotousuario();
    callbiousuario();
    

    $('#user_bio').blur(function() {
        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                set_user_bio:"s",
                text:$('#user_bio').val()
            },
            dataType: 'html', 
            success: function(data) {
                console.log("Bio Atualizada com Sucesso!");
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
        // Seu código aqui para lidar com o evento de perda de foco do textarea
    });
   
    function callbiousuario(){
        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                get_user_bio:"s"
            },
            dataType: 'html', 
            success: function(data) {
                $("#user_bio").empty().append(data);
                $("#user_bio").val( $("#user_bio").val().substring(1) );
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    };
    
    $(document).on("click", ".perfil", function(){
        window.location.href = "./../perfil/perfil.php";
    });
    $(document).on("click", ".chats", function(){
        window.location.href = "./../chat/chat.php";
    });
    $(document).on("click", ".feeding", function(){
        window.location.href = "./../feeding/feeding.php";
    });
    $(document).on("click", ".tabuleiro", function(){
        window.location.href = "./../tabuleiro/tabuleiro.php";
    });
    
    
    $(document).on("click", "#add_pessoa_amizade", function(){
        var lista_id_amigos = [];

        $.ajax({
            type: 'POST',
            url: './../api/chat.api.php',
            data: {
                get_all_friends_id_by_id: 'S'
            },
            dataType: 'json',
            async: false, // Tornando a requisição síncrona
            success: function(data) {
                lista_id_amigos = data;
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });


        $.ajax({
            type: 'POST',
            url: './../api/chat.api.php',
            data: {
                get_all_users: 'S',
                input:"",
                contexto:"adicionar_amigo",
                lista_id_amigos:lista_id_amigos
            },
            dataType: 'html',
            success: function(data) {
                $("#query-pessoas-adicionar-amizade").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });


    $(document).on("click", "#add_pessoa_amizade_pesquisar", function(){
        var input= document.getElementById("input_nome_pessoa_amizade_pesquisar").value;
        var lista_id_amigos = [];

        $.ajax({
            type: 'POST',
            url: './../api/chat.api.php',
            data: {
                get_all_friends_id_by_id: 'S'
            },
            dataType: 'json',
            async: false, // Tornando a requisição síncrona
            success: function(data) {
                lista_id_amigos = data;
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
        
        $.ajax({
            type: 'POST',
            url: './../api/chat.api.php',
            data: {
                get_all_users: 'S',
                input:input,
                contexto:"adicionar_amigo",
                lista_id_amigos:lista_id_amigos
            },
            dataType: 'text',
            success: function(data) {
                $("#query-pessoas-adicionar-amizade").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });




    $(document).on("click", ".abrir_chat_privado", function(){
        var id_user_alvo=$(this).attr('id').substring(13);
        var nome_user_alvo=$(this).attr('data-nome');

        $.ajax({
            type: 'POST',
            url: './../api/chat.api.php',
            data: {
                criar_sala: 'S',
                nome_sala:"Chat Privado",
                id_user_alvo:id_user_alvo
            },
            dataType: 'text',
            success: function(data) {
                window.location.href = "./../chat/chat.php";
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });

    $(document).on("click", ".excluir_amizade", function(){
        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                excluir_amizade: 'S',
                id_amizade:$(this).attr('id').split("_")[1]
            },
            dataType: 'html',
            success: function(data) {
                toastr.success('Amizade Desfeita.', 'Sucesso!');
                $("#query-requisicoes_de_amizade").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });
    
    $(document).on("click", "#lista_de_amigos", function(){
        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                getall_amigos:'S'
            },
            dataType: 'html',
            success: function(data) {
                $("#query-pessoas-lista-amigos").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });


    $(document).on("click", ".aceitar_amizade", function(){

        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                aceitar_amizade: 'S',
                id_pedido:$(this).attr('id').split("_")[1]
            },
            dataType: 'html',
            success: function(data) {
                toastr.success('Pedido de Amizade Aceito.', 'Sucesso!');
                $("#query-requisicoes_de_amizade").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });

    $(document).on("click", ".recusar_amizade", function(){

        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                recusar_amizade: 'S',
                id_pedido:$(this).attr('id').split("_")[1]
            },
            dataType: 'html',
            success: function(data) {
                toastr.success('Pedido de Amizade Recusado.', 'Sucesso!');
                $("#query-requisicoes_de_amizade").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });


    $(document).on("click", "#requisicoes_de_amizade", function(){
        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                get_all_requisicoes_de_amizade_desse_user: 'S'
            },
            dataType: 'html',
            success: function(data) {
                $("#query-requisicoes_de_amizade").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });

    });
    
    
    

    $(document).on("click", ".adicionar_amizade", function(){
        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                enviar_pedido_amizade:"S",
                id_requisitado:$(this).attr('id').substring(5)
            },
            dataType: 'text',
            success: function(data) {
                toastr.success('Pedido de amizade enviado.', 'Sucesso!');
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
        
    });


    $(document).on("click", "#trocar_foto_perfil_confirmar", function(){
        link=$("#input_trocar_foto_perfil").val();
        $("#input_trocar_foto_perfil").val("");
        
        $("#pfp").attr("src",link);
        user_id=$("#trocar_foto_perfil_confirmar").val();
        $.ajax({
            type: 'POST',
            url: './../api/perfil.api.php',
            data: {
                trocar_pfp:"s",
                link_foto:link,
                user_id:user_id
            },
            dataType: 'text',
            success: function(data) {
                toastr.success('Foto trocada com sucesso.', 'Sucesso!');
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });

    });

});