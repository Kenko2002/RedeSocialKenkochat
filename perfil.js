function callUserId(){
    $.ajax({
        type: 'POST',
        url: './api/perfil.api.php',
        data: {
            get_user_id: 'S'
        },
        dataType: 'json', 
        success: function(data) {
            var user_id = data.id_user;
            console.log("ID do usuário: " + user_id);
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
        url: './api/perfil.api.php',
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

    

    $(document).on("click", "#trocar_foto_perfil_confirmar", function(){
        link=$("#input_trocar_foto_perfil").val();
        $("#input_trocar_foto_perfil").val("");
        
        $("#pfp").attr("src",link);
        user_id=$("#trocar_foto_perfil_confirmar").val();
        $.ajax({
            type: 'POST',
            url: './api/perfil.api.php',
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