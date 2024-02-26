function callNomeUser(){
    $.ajax({
        type: 'POST',
        url: './api/chat.api.php',
        data: {
            get_user_name: 'S'
        },
        dataType: 'text',
        success: function(data) {
            //console.log(data);
            nome_user=data;
            nickname =data;
            var titulo=document.getElementById("div_name");
            var conteudoAtual = titulo.innerHTML;
            var novoConteudo = conteudoAtual + " - " +nome_user;
            titulo.innerHTML = novoConteudo;
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
    
}

function callUserId(){
    $.ajax({
        type: 'POST',
        url: './api/chat.api.php',
        data: {
            get_user_id: 'S'
        },
        dataType: 'text',
        success: function(data) {
            user_id=data;
            return data;
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
}

$(document).ready(function() {

    var mensagemInput = document.getElementById("message");
    var botaoEnviar = document.getElementById("send");
    var nome_user;
    var user_id=callUserId();
    var id_sala=1;

    mensagemInput.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) { // Verifica se a tecla pressionada é a tecla "Enter" (código 13)
            botaoEnviar.click(); // Clique no botão "send"
        }
    });

    callUserId();
    callNomeUser();
    callAtualizarChat();
    callSalasDeChat();
    setInterval(callAtualizarChat, 3000);
    setInterval(callSalasDeChat,3000);


    $(document).on("click", ".perfil", function(){
        window.location.href = "perfil.php";
    });


    $(document).on("click", ".remover_pessoa", function(){
        id_pessoa_alvo=$(this).attr('id').substring(10);
        id_sala=id_sala;
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                remover_relacionamento_pessoa_sala:'S',
                id_sala:id_sala,
                id_pessoa:id_pessoa_alvo
            },
            dataType: 'text',
            success: function(data) {
                callSalasDeChat();
                
                $.ajax({
                    type: 'POST',
                    url: './api/chat.api.php',
                    data: {
                        abrir_info_sala:'S',
                        id_sala:id_sala
                    },
                    dataType: 'text',
                    success: function(data) {
                        toastr.success('Pessoa removida com sucesso.', 'Sucesso!');
                        $("#query-informações-da-sala").empty().html(data); //INSIRA AQUI A DIV DE DESTINO
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

    $(document).on("click", ".abrir_info_sala", function(){
        
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                abrir_info_sala:'S',
                id_sala:id_sala
            },
            dataType: 'text',
            success: function(data) {
                $("#query-informações-da-sala").empty().html(data); //INSIRA AQUI A DIV DE DESTINO
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });
    
    
    $(document).on("click", ".adicionar_pessoa", function(){
        id_sala=id_sala;
        id_pessoa=$(this).attr('id').substring(5);
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                criar_relacao_pessoa_sala_nao_admin:'S',
                id_sala:id_sala,
                id_pessoa:id_pessoa
            },
            dataType: 'text',
            success: function(data) {
                callSalasDeChat();
                toastr.success('Pessoa adicionada com sucesso.', 'Sucesso!');
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });
    
    $(document).on("click", ".cls", function(){
        document.getElementById("message").value="/cls";
    });
    $(document).on("click", ".deleteroom", function(){
        document.getElementById("message").value="/deleteroom"
    });
    //variaveis globais acima

    // Sua inicialização de código aqui, se necessário
    $(document).on("click", "#criar_sala_confirmar", function(){
        var nome = document.getElementById("input_room_name").value;
        console.log(callUserId());
        if(nome==""){return;}
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                criar_sala: 'S',
                nome_sala:nome
            },
            dataType: 'text',
            success: function(data) {
                callSalasDeChat();
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });

    });

    $(document).on("click", "#add_pessoa_chat", function(){
        var input= document.getElementById("input_nome_pessoa_pesquisar").value="";
        $("#query-pessoas-adicionar-ao-chat").empty();
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                get_all_users: 'S',
                input:""
            },
            dataType: 'text',
            success: function(data) {
                $("#query-pessoas-adicionar-ao-chat").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });
    
    $(document).on("click", "#add_pessoa_chat_pesquisar", function(){
        var input= document.getElementById("input_nome_pessoa_pesquisar").value;
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                get_all_users: 'S',
                input:input
            },
            dataType: 'text',
            success: function(data) {
                $("#query-pessoas-adicionar-ao-chat").empty().html(data);
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    });

    $(document).on("click", ".botao_sala", function(){
        id_sala = $(this).attr("id").substring(11);
        nome_sala=$(this).attr("data");
        //console.log("ID da sala: " + id_sala);
        //console.log("nome da sala: "+nome_sala);
        $("#div_name").val(nome_sala);
        var elemento = document.getElementById("div_name");
        
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                get_user_name: 'S'
            },
            dataType: 'text',
            success: function(data) {
                elemento.innerHTML = nome_sala + " - " + data;
                callAtualizarChat();
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });

    });


    $(document).on("click", "#send", function() {
        var mensagem = document.getElementById("message").value;
        if(mensagem==""){return;}
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                get_user_id: 'S'
            },
            dataType: 'json',
            success: function(data) {
                user_id=data;
                
                $.ajax({
                    type: 'POST',
                    url: './api/chat.api.php',
                    data: {
                        enviar_mensagem:'S',
                        id_user:user_id,
                        mensagem:mensagem,
                        id_sala:id_sala
                    },
                    dataType: 'text',
                    success: function(data) {
                        callAtualizarChat();
                        var customDiv = document.querySelector('.custom-div');
                        customDiv.scrollTop = customDiv.scrollHeight;
                        $("#message").val('');
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

    function callSalasDeChat(){
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                query_salas: 'S',
            },
            dataType: 'text',
            success: function(data) {
                $("#salas-query").empty().html(data);
                var customDiv = document.querySelector('.custom-div');
                customDiv.scrollTop = customDiv.scrollHeight;
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    }

    function callAtualizarChat(){
        $.ajax({
            type: 'POST',
            url: './api/chat.api.php',
            data: {
                query_chat: 'S',
                id_sala:id_sala
            },
            dataType: 'text',
            success: function(data) {
                $("#chat-query").empty().html(data);
                var customDiv = document.querySelector('.custom-div');
                customDiv.scrollTop = customDiv.scrollHeight;
            },
            error: function(xhr, status, error) {
                console.log("Erro na solicitação AJAX: " + status + " - " + error);
            }
        });
    }

    
    

});
