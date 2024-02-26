
     $(document).on('click','#login', function(e){
        var user= $('#input1').val();
        var senha=$('#input2').val();

        if (user==""||senha==""){
            toastr.error('Preencha todos os campos!', 'Erro!');
            return;
        }
        
        $.ajax({
            type:'POST',
            url:'./api/login.api.php',
            data: {
                'login':'S',
                'user':user,
                'senha':senha
            },
            dataType:'html',
            success: function(data){

                if((data.indexOf("Login Efetuado!") !== -1)){
                    window.location.href = "chat.php";
                    toastr.success('Login efetuado com sucesso !', 'Sucesso!');
                }
                else{
                    toastr.error('Login Inválido!', 'Erro!');
                }
            },
            error: function(e){
                console.log("Falha");
            }
     });
    });
            



     $(document).on('click','#cadastrar', function(e){
        var user= $('#input1').val();
        var senha=$('#input2').val();
       

        if(senha.length >= 10){
            $.ajax({
                type:'POST',
                url:'./api/login.api.php',
                data: {
                    'cadastrar':'S',
                    'user':user,
                    'senha':senha
                },
                dataType:'html',
                success: function(data){
                    console.log(data);
                    if((data.indexOf("Usuario ja existe!") == -1)){
                        toastr.success('Cadastrado!', 'Sucesso!');
                        $("#login").click();
                    }
                    else{
                        toastr.error('Usuario já existe!', 'Erro!');
                    }
                },
                error: function(e) {
                    toastr.error('Falha no cadastro!', 'Erro!');
                }
            });
        }
        else{
            toastr.error('Insira pelomenos 10 caracteres!', 'Erro!');
        }

       


        });
     

/*
    LOGIN VÁLIDO:
    user: MAFE
    senha: 12345

    user:RENZO
    senha:22102002

    user:ISA
    senha:123
*/