

// Selecionando o sino e o menu
const sino = $("#sino");
const menu = $('#menu');
var num_notif=0;

$(document).on("click", "#sino", function(){
  // Alternando a exibição do menu entre 'block' e 'none'
  if ($("#menu").css("display")=="block") {
    $("#menu").css("display","none");
  } else {
    if(num_notif!=0){
      $("#menu").css("display","block");
    }
  }
});



$(document).on("click", ".menu-item", function(){
  //console.log( 'Mensagem:'+ $(this).attr("value") );
  $("#menu").css("display","none");
  var link_pagina = $(this).attr("href");

  
  parent.postMessage({ action: link_pagina }, '*');
  

});


$(document).on("click", ".feeding_notify", function(){
  $("#menu").css("display","none");
  var id_post = $(this).attr("id_postagem");

  $('#modal_comentarios').show();
  
  $.ajax({
      type: 'POST',
      url: './../api/feeding.api.php',
      data: {
          query_comentarios:'S',
          id_postagem:id_post
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

  



$(document).on("click", function(event) {
  if ($(event.target).closest("#sino").length) {
    console.log("Clique ocorreu na div de ID 'sino'");
  } else {
      $("#menu").css("display","none");
  }
});


function call_get_num_notificacoes (){
  $.ajax({
    type: 'POST',
    url: './../api/notificacao.api.php',
    data: {
      get_num_notificacoes:'S'
    },
    dataType: 'json',
    success: function(data) {
        $("#num_notificacoes").empty().html(data);
        num_notif=data;
    },
    error: function(xhr, status, error) {
        console.log("Erro na solicitação AJAX: " + status + " - " + error);
    }
  });
}


function callget_all_notificacoes() {
  $.ajax({
    type: 'POST',
    url: './../api/notificacao.api.php',
    data: {
      get_all_notificacoes:'S'
    },
    dataType: 'html',
    success: function(data) {
        $("#menu").empty().html(data);
    },
    error: function(xhr, status, error) {
        console.log("Erro na solicitação AJAX: " + status + " - " + error);
    }
  });
  call_get_num_notificacoes();
  
  
}

$(document).ready(function() {
  callget_all_notificacoes();
  setInterval(callget_all_notificacoes, 3000);

  
  
});