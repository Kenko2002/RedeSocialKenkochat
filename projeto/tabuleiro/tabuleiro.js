



function syncBackgroundPosition() {
    var board_container = $(".board_container");
    var scrollLeft = board_container.scrollLeft();
    var scrollTop = board_container.scrollTop();
    board_container.css('background-position', -scrollLeft + 'px ' + -scrollTop + 'px');
}

function updateBoardInfo(){
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
          atualizar_infos_tabuleiro:'S',
          id_tabuleiro:id_tabuleiro_atual,
          cols:$("#cols").val(),
          rows:$("#rows").val(),
          background:$("#background").val(),
          mostrar_grid:$("#check_mostrar_grid").prop('checked')
        },
        dataType: 'html',
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
      });
}

function updateBoard() {




    
    
    const cols = $('#cols').val();
    const rows = $('#rows').val();
    const background = `url('${$('#background').val()}')`;

    $('html').css('--cols', cols);
    $('html').css('--rows', rows);

    var board = $('.board');
    

    

    var board_container= $(".board_container");
    board_container.css('background-image', background);
    board_container.css('background-size', '100% 100%');
    board_container.css('background-position', 'center');
    board_container.css('background-repeat', 'no-repeat');
    board_container.css('padding', '0px');

    
    board.empty();
    


    for (let i = 0; i < cols * rows; i++) {
        console.log("renderizei as celulas");
        const cell = $('<div></div>');
        cell.attr('row', Math.floor(i / cols));
        cell.attr('col', i % cols);



        //id="row_0_col_0"
        cell.attr('id', "row_" + Math.floor(i / cols) + "_col_" + i % cols);


        if (cols > 16) {
            cell.css('width', '40px');
            board_container.css('background-size', (40 * cols).toString()+"px ,"+(40 * rows).toString()+"px"  );
        }else{
            cell.css('width', $(".board_container").width() /cols );
        }

        if (rows > 12) {
            cell.css('height', '40px');
            board_container.css('background-size', (40 * cols).toString()+"px ,"+(40 * rows).toString()+"px"  );
        }else{
            cell.css('height', $(".board_container").height() /rows );
        }

        
        cell.addClass('cell');
        board.append(cell);
    }

    syncBackgroundPosition();


    if($("#check_mostrar_grid").prop('checked')===false){
        $(".cell").css('border-width',"0px");
    }

}


function carregar_tabuleiro_do_banco() {
    updateBoard();
    try {
        const data =$.ajax({
            type: 'GET', // Usando método GET
            url: './../api/tabuleiro.api.php',
            data: {
                get_tabuleiro_by_id: "s",
                id_tabuleiro: id_tabuleiro_atual
            },
            dataType: 'json',
            success: function(data) {


        
        
                // O código abaixo só será executado após a conclusão bem-sucedida da solicitação AJAX
                // console.log(data);
                if( !$('#mudar_tabuleiro').is(':visible') ){
                    $("#cols").val(data["largura"]);
                    $("#rows").val(data["altura"]);
                    $("#background").val(data["link_foto"]);
                    $("#tabuleiro_info").text(data["nome"]);
                    if (data["mostrar_grid"] == "s") {
                        $("#check_mostrar_grid").prop('checked', true);
                    } else {
                        $("#check_mostrar_grid").prop('checked', false);
                    }
                }

            },
            
        });

        
    } catch (error) {
        console.log("Erro na solicitação AJAX: " + error);
    }

    //carregando os tokens
    $.ajax({
        type: 'GET', // Usando método GET
        url: './../api/tabuleiro.api.php',
        data: {
            get_all_tokens_desse_tabuleiro: "s",
            id_tabuleiro: id_tabuleiro_atual
        },
        
        dataType: 'json',
        success: function(data) {
            //console.log(data);
            data.forEach(function(result) {
                //console.log("#row_" + result.x + "_col_" + result.y);
                //console.log('<div id="token_' + result.id + '" class="token"></div>');


                    var tokenElement = $('<div class="token" id="token_' + result.id + '"></div>');
                    tokenElement.css('background-image','url("' + result.link_foto + '")');
                    tokenElement.css("z-index","2");
                    
                    $("#row_" + result.x + "_col_" + result.y).append( tokenElement );
                    console.log("renderizei os tokens");

            });
            
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
}






var id_tabuleiro_atual=1;
var id_token=0;
var id_area_effect=0;

$(document).ready(function() {


    carregar_tabuleiro_do_banco();
    setInterval(carregar_tabuleiro_do_banco, 2000);
    callTabuleiros()
    setInterval(callTabuleiros, 2000);
    callAreaEfeitos();
    setInterval(callAreaEfeitos, 2000);
    

    $(".board_container").scroll(function() {
        syncBackgroundPosition();
    });

});






$(document).on("mousedown", function(event) {
    var distanceX=0;
    var distanceY=0;
    var id_token=0;
    var id_area_effect=0;
    var eh_cell=false;
    // Quando o botão do mouse é pressionado
    // Registra o ponto inicial do arraste
    var startX = event.pageX;
    var startY = event.pageY;
    //console.log("start X:"+startX+" Y:"+startY);
    //console.log(event.target);
    if ($(event.target).hasClass("token")) {
        id_token=$(event.target).attr("id");
    }
    if ($(event.target).hasClass("cell")) {
        eh_cell=true;
    }
    if ($(event.target).hasClass("area_effect")){
        id_area_effect=$(event.target).attr("id_effect_area");
    }

    // Adiciona um ouvinte de evento de movimento do mouse
    $(document).on("mousemove", function(event) {
        if($(event.target).hasClass("token") || $(event.target).hasClass("cell")){
            distanceX = event.pageX;
            distanceY = event.pageY; 
            


            // Movendo o token selecionado com o mouse.
            if (id_token !=0) {
                $("#"+id_token).css("position","absolute");
                $("#"+id_token).css({left: distanceX-420, top: distanceY-30});
                $(event.target).css("cursor" ,"grabbing");
            }
            if(id_area_effect!=0){
                $(event.target).css("cursor" ,"grabbing");
            }
        





            //permitindo que o usuário mova o scroller clicando na div.
            if(eh_cell){
                var board_container = $(".board_container");
                var scrollLeft = board_container.scrollLeft();
                var scrollTop = board_container.scrollTop();

                //console.log("SL:"+scrollLeft);
                //console.log("distance-start:"+(distanceX-startX));
                //console.log("distance-start:"+(distanceY-startY));

                board_container.scrollLeft(scrollLeft+distanceX-startX);
                board_container.scrollTop(scrollTop+distanceY-startY);

            }


        }
    });

    // Adiciona um ouvinte de evento de soltura do mouse
    $(document).on("mouseup", function(event) {
        eh_cell=false;
        // Atualiza a posição inicial para o próximo movimento
        startX = event.pageX;
        startY = event.pageY;

        try{
        id_token=id_token.substring(6);
        }catch{""}

        console.log(event.target);
        

        if (id_token!=0 && $(event.target).hasClass("cell")) {
            //console.log("o mouse foi solto na célula "+$(event.target).attr("id"));
            const [num1, num2] = extrairNumerosDaString($(event.target).attr("id"));
            $.ajax({
                type: 'POST',
                url: './../api/tabuleiro.api.php',
                data: {
                  atualizar_posicao_token:'S',
                  id_token:id_token,
                  id_tabuleiro:id_tabuleiro_atual,
                  x:num1,
                  y:num2
                },
                dataType: 'html',
                success: function(data) {
                    $("#menu").empty().html(data);
                },
                error: function(xhr, status, error) {
                    console.log("Erro na solicitação AJAX: " + status + " - " + error);
                }
              });
        }
        if (id_area_effect!=0 && $(event.target).hasClass("cell")) {
            //console.log("o mouse foi solto na célula "+$(event.target).attr("id"));
            const [num1, num2] = extrairNumerosDaString($(event.target).attr("id"));
            $.ajax({
                type: 'POST',
                url: './../api/tabuleiro.api.php',
                data: {
                  atualizar_posicao_area_effect:'S',
                  id_area_effect:id_area_effect,
                  id_tabuleiro:id_tabuleiro_atual,
                  x:num1,
                  y:num2
                },
                dataType: 'html',
                success: function(data) {
                    $("#menu").empty().html(data);
                },
                error: function(xhr, status, error) {
                    console.log("Erro na solicitação AJAX: " + status + " - " + error);
                }
              });
        }

        if ($(event.target).hasClass("token")) {
            //console.log("o mouse foi solto no token "+$(event.target).attr("id"));
        }
        if(id_token!=0 && $(event.target).hasClass("cell")){
            //console.log("o token "+ id_token +" foi solto na casa "+$(event.target).attr("id"));
        }
        
        //console.log("end X:"+(startX+distanceX)+" Y:"+(startY+distanceY));
        // Remove os ouvintes de evento de movimento e soltura do mouse
        $(document).off("mousemove");
        $(document).off("mouseup");
    });
});


function extrairNumerosDaString(string) {
    const numeros = string.match(/\d+/g);
    if (numeros && numeros.length >= 2) {
        return [parseInt(numeros[0]), parseInt(numeros[1])];
    } else {
        return null;
    }
}

function arraysSaoIguais(array1, array2) {
    // Verifica se os arrays têm o mesmo comprimento
    if (array1.length !== array2.length) {
        return false;
    }


    // Itera pelos elementos e compara-os
    for (let i = 0; i < array1.length; i++) {
        if (JSON.stringify(array1[i]) !== JSON.stringify(array2[i])) {
            return false;
        }
    }

    // Se todas as comparações forem iguais, os arrays são considerados iguais
    return true;
}



























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

function abrirModalTabuleiroInfo() {
    $("#mudar_tabuleiro").modal("show");
}


$(document).on("click", "#criar_tabuleiro_confirmar", function(){
    var nome = document.getElementById("input_tabuleiro_name").value;
    if(nome==""){return;}
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            criar_tabuleiro: 'S',
            nome_tabuleiro:nome
        },
        dataType: 'text',
        success: function(data) {
            callTabuleiros();
            $("#input_tabuleiro_name").val("");
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });

});

function callTabuleiros(){
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            query_tabuleiros: 'S',
        },
        dataType: 'text',
        success: function(data) {
            $("#seletor-tabuleiros-query").empty().html(data);
            var customDiv = document.querySelector('#seletor-tabuleiros-query');
            customDiv.scrollTop = customDiv.scrollHeight;
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
}
function hexToRgba(hex, opacity) {
    // Remover o '#' se estiver presente
    hex = hex.replace('#', '');
    
    // Converter para valores de RGB
    var r = parseInt(hex.substring(0, 2), 16);
    var g = parseInt(hex.substring(2, 4), 16);
    var b = parseInt(hex.substring(4, 6), 16);
    
    // Retornar a notação rgba()
    return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + opacity + ')';
}


function callAreaEfeitos(){
    $.ajax({
        type: 'GET',
        url: './../api/tabuleiro.api.php',
        data: {
            query_area_efeitos: 'S',
            id_tabuleiro:id_tabuleiro_atual
        },
        dataType: 'json',
        success: function(data) {

            if (Array.isArray(data)) {
                data.forEach(function(result) {

                    var x=result.x;
                    var y=result.y;
                    var altura=result.altura;
                    var largura=result.largura;
                    var id= result.id;
                    for (var i = 0; i<altura ; i++) {
                        for(var j=0; j<largura; j++){
                            //console.log("para i="+i+" e j="+j);
                            //console.log("x+i: "+ (parseInt(x)+parseInt(i)));
                            //console.log("x+j: "+ (parseInt(x)+parseInt(j)));
                            $("#row_" + (parseInt(x)+parseInt(i)) + "_col_" +(parseInt(y)+parseInt(j)) ).css('background-color', hexToRgba(result.cor,'0.3'));
                            $("#row_" + (parseInt(x)+parseInt(i)) + "_col_" +(parseInt(y)+parseInt(j)) ).css('cursor', 'grab');
                            $("#row_" + (parseInt(x)+parseInt(i)) + "_col_" +(parseInt(y)+parseInt(j)) ).addClass("area_effect");
                            $("#row_" + (parseInt(x)+parseInt(i)) + "_col_" +(parseInt(y)+parseInt(j)) ).attr("id_effect_area", id);
                            $("#row_" + (parseInt(x)+parseInt(i)) + "_col_" +(parseInt(y)+parseInt(j)) ).attr("altura", altura);
                            $("#row_" + (parseInt(x)+parseInt(i)) + "_col_" +(parseInt(y)+parseInt(j)) ).attr("largura", largura);
                        }
                    }
                    console.log("renderizei as effect_areas");


                });

            } else {
                console.log("Os dados não são do tipo array. Não é possível iterar sobre eles.");
            }
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
    
}

$(document).on("click", ".botao_tabuleiro", function(){
    id_tabuleiro_atual=$(this).attr("id").substring(16);
    carregar_tabuleiro_do_banco();
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            atualizar_session_eh_admin: 'S',
            id_tabuleiro:id_tabuleiro_atual
        },
        dataType: 'json',
        success: function(data) {
            
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});


$(document).on("click", ".delete_tabuleiro", function(){
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            apagar_tabuleiro: 'S',
            id_tabuleiro:id_tabuleiro_atual
        },
        dataType: 'json',
        success: function(data) {
            callTabuleiros();
            if(data=="Tabuleiro Apagado"){
                toastr.success('Tabuleiro apagado!.', 'Sucesso!');
                id_tabuleiro_atual=1;
            }   
            if(data=='Permissão Negada!'){
                toastr.error('Apenas Admins podem apagar o Tabuleiro.', 'Erro!');
            }
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});


$(document).on("click", ".add_pessoa_tabuleiro", function(){
    var input= document.getElementById("input_nome_pessoa_pesquisar").value="";
    $("#query-pessoas-adicionar-ao-tabuleiro").empty();
    $.ajax({
        type: 'POST',
        url: './../api/chat.api.php',
        data: {
            get_all_users: 'S',
            input:"",
            contexto:"adicionar_pessoa_ao_tabuleiro"
        },
        dataType: 'text',
        success: function(data) {
            $("#query-pessoas-adicionar-ao-tabuleiro").empty().html(data);
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});

$(document).on("click", ".adicionar_pessoa_tabuleiro", function(){
    id_sala=id_tabuleiro_atual;
    id_pessoa=$(this).attr('id').substring(5);
    $.ajax({
        type: 'POST',
        url: './../api/chat.api.php',
        data: {
            criar_relacao_pessoa_tabuleiro_nao_admin:'S',
            id_tabuleiro:id_tabuleiro_atual,
            id_pessoa:id_pessoa
        },
        dataType: 'text',
        success: function(data) {
            callTabuleiros();
            toastr.success('Pessoa adicionada com sucesso.', 'Sucesso!');
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});


$(document).on("click", "#criar_token_confirmar", function(){
    imagem_token=$("#link_imagem_add_token").val();
    id_tabuleiro=id_tabuleiro_atual;
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            criar_token:'S',
            id_tabuleiro:id_tabuleiro_atual,
            imagem_token:imagem_token
        },
        dataType: 'text',
        success: function(data) {
            callTabuleiros();
            toastr.success('Pessoa adicionada com sucesso.', 'Sucesso!');
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});





$(document).on("click", "#editar_area_efeito_confirmar", function(){
    altura=$("#editar_altura_area_efeito").val();
    largura=$("#editar_largura_area_efeito").val();
    cor=$("#colorPicker_editar_area").val();

    x=$("#x_area_efeito").val();
    y=$("#y_area_efeito").val();


    id_tabuleiro=id_tabuleiro_atual;
    
    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            editar_area_efeito:'S',
            id_tabuleiro:id_tabuleiro_atual,
            altura:altura,
            largura:largura,
            id_area_effect:id_area_effect,
            cor:cor
        },
        dataType: 'text',
        success: function(data) {
            carregar_tabuleiro_do_banco();
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});


$(document).on("click", "#excluir_area_efeito_confirmar", function(){

    $.ajax({
        type: 'GET',
        url: './../api/tabuleiro.api.php',
        data: {
            excluir_area_efeito:'S',
            id_area_effect:id_area_effect
        },
        dataType: 'text',
        success: function(data) {
            carregar_tabuleiro_do_banco();
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });
});


$(document).on("click", "#criar_area_efeito_confirmar", function(){
    altura=$("#altura_area_efeito").val();
    largura=$("#largura_area_efeito").val();
    x=$("#x_area_efeito").val();
    y=$("#y_area_efeito").val();
    cor=$("#colorPicker_criar_area").val();

    $("#altura_area_efeito").val("");
    $("#largura_area_efeito").val("");
    $("#y_area_efeito").val("");
    $("#x_area_efeito").val("");
    $("#colorPicker_criar_area").val("");

    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            criar_area_efeito:'S',
            id_tabuleiro:id_tabuleiro_atual,
            altura:altura,
            largura:largura,
            x,
            y,
            cor
        },
        dataType: 'text',
        success: function(data) {
            carregar_tabuleiro_do_banco();
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        }
    });


});




$(document).on("click", "#token_click_confirmar", function(){

    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            set_token_image:'S',
            id_token:id_token,
            link_foto:$("#input_token_url_edit").val()
        },
        dataType: 'text',
        success: function(data) {
            toastr.success("Token alterado com sucesso.","Sucesso!");
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        } 
      });

      $('#modal_token_click').modal('show');

});


$(document).on("click", "#excluir_token_click_confirmar", function(){

    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            delete_token:'S',
            id_token:id_token
        },
        dataType: 'text',
        success: function(data) {
            toastr.success("Token Apagado com sucesso.","Sucesso!");
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        } 
      });


});


$(document).on("click", "#token_click_duplicar", function(){

    $.ajax({
        type: 'POST',
        url: './../api/tabuleiro.api.php',
        data: {
            duplicar_token:'S',
            id_token:id_token
        },
        dataType: 'text',
        success: function(data) {
            toastr.success("Token duplicado com sucesso.","Sucesso!");
        },
        error: function(xhr, status, error) {
            console.log("Erro na solicitação AJAX: " + status + " - " + error);
        } 
      });

      $('#modal_token_click').modal('show');

});


    

