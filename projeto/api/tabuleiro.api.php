<?php
include "conexao.php";
session_start(); 
?>

<?php

if(isset($_GET["get_tabuleiro_by_id"])){
    $id_tabuleiro=$_GET["id_tabuleiro"];

    $sql="SELECT * FROM tabuleiros WHERE id=$id_tabuleiro";
    $results = $conn->query($sql);

    foreach($results as $result){
        echo json_encode($result);
    }
    
}

if(isset($_GET["query_area_efeitos"])){
    $id_tabuleiro=$_GET["id_tabuleiro"];

    $sql="SELECT * FROM area_efeito WHERE id_tabuleiro=$id_tabuleiro";
    $results = $conn->query($sql);

    $data = array(); // Inicialize um array vazio para armazenar os resultados

    foreach ($results as $result) {
        $data[] = $result; // Adicione cada resultado ao array $data
    }
    
    echo json_encode($data);
}

if(isset($_POST["atualizar_session_eh_admin"])){
    $_SESSION["eh_admin"]="n";
    $id_tabuleiro=$_POST["id_tabuleiro"];
    $sql="SELECT * FROM usuario_tabuleiro WHERE id_user=".$_SESSION["id_user"]." AND id_tabuleiro=".$id_tabuleiro;
    $results = $conn->query($sql);

    foreach($results as $result){
        if($result["eh_admin"]=="s"){
            $_SESSION["eh_admin"]="s";
        }
    }
    echo json_encode($_SESSION["eh_admin"]);
}

if(isset($_POST["apagar_tabuleiro"])){
    if($_SESSION["eh_admin"]=="s"){
        $id_tabuleiro=$_POST["id_tabuleiro"];
        $sql="DELETE FROM tabuleiros WHERE id=".$id_tabuleiro;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sql="DELETE FROM usuario_tabuleiro WHERE id_user=".$_SESSION["id_user"]." AND id_tabuleiro=".$id_tabuleiro;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        echo json_encode("Tabuleiro Apagado");
    }else{
        echo json_encode("Permissão Negada!");
    }

    
}

if(isset($_POST["atualizar_infos_tabuleiro"])){
    
    $id_tabuleiro=$_POST["id_tabuleiro"];
    $cols=$_POST["cols"];
    $rows=$_POST["rows"];
    $background=$_POST["background"];
    $grid=$_POST["mostrar_grid"];

    if($grid=="true"){
        $grid="s";
    }else{
        $grid="n";
    }


    $sql="UPDATE tabuleiros
    SET 
        largura = $cols, 
        altura = $rows, 
        link_foto = '$background',
        mostrar_grid = '$grid'

    WHERE id=$id_tabuleiro";
    $results = $conn->query($sql);
}


if(isset($_POST["atualizar_posicao_token"])){
    $id_token=$_POST["id_token"];
    $id_tabuleiro=$_POST["id_tabuleiro"];
    $y=$_POST["y"];
    $x=$_POST["x"];

    $sql="UPDATE tokens
    SET x = $x, 
        y = $y
        
    WHERE id = $id_token";
    $results = $conn->query($sql);

    $sql="SELECT id_tabuleiro FROM tokens WHERE id=$id_token";
    $results = $conn->query($sql);

    foreach($results as $result){
        $sql="UPDATE tabuleiros SET ultima_atualizacao=NOW() WHERE id = $id_tabuleiro";
        $ans = $conn->query($sql);
    }
    
}

if(isset($_POST["atualizar_posicao_area_effect"])){
    $id_area_effect=$_POST["id_area_effect"];
    $id_tabuleiro=$_POST["id_tabuleiro"];
    $y=$_POST["y"];
    $x=$_POST["x"];

    $sql="UPDATE area_efeito
    SET x = $x, 
        y = $y
        
    WHERE id = $id_area_effect";
    $results = $conn->query($sql);


    $sql="UPDATE tabuleiros SET ultima_atualizacao=NOW() WHERE id = $id_tabuleiro";
    $ans = $conn->query($sql);
}


if(isset($_POST["editar_area_efeito"])){
    $id_area_effect=$_POST["id_area_effect"];
    $id_tabuleiro=$_POST["id_tabuleiro"];
    $altura=$_POST["altura"];
    $largura=$_POST["largura"];
    $cor=$_POST["cor"];

    if($cor !=""){
        $sql="UPDATE area_efeito
        SET altura = $altura, 
        largura = $largura,
        cor='$cor'
        
        WHERE id = $id_area_effect";
    }
    else{
        $sql="UPDATE area_efeito
        SET altura = $altura, 
        largura = $largura
        
    WHERE id = $id_area_effect";
    }

    $results = $conn->query($sql);


    $sql="UPDATE tabuleiros SET ultima_atualizacao=NOW() WHERE id = $id_tabuleiro";
    $ans = $conn->query($sql);
}

if(isset($_POST["get_ultima_atualizacao_tabuleiro"])){
    $id_tabuleiro=$_POST["id_tabuleiro"];
    $sql="SELECT ultima_atualizacao FROM tabuleiros WHERE id=$id_tabuleiro";
    $results = $conn->query($sql);

    $data = array(); // Inicialize um array vazio para armazenar os resultados

    foreach ($results as $result) {
        $data[] = $result; // Adicione cada resultado ao array $data
    }
    
    echo json_encode($data);
}


if(isset($_GET["get_all_tokens_desse_tabuleiro"])){
    $id_tabuleiro=$_GET["id_tabuleiro"];
    $sql="SELECT tabuleiros.ultima_atualizacao,
                 tokens.id,tokens.link_foto,tokens.id_tabuleiro,tokens.id_usuario,tokens.x,tokens.y
            FROM tokens
            INNER JOIN tabuleiros ON tabuleiros.id=tokens.id_tabuleiro
            WHERE id_tabuleiro=$id_tabuleiro";
    $results = $conn->query($sql);

    if ($results) {
        $output = array();
        while ($row = $results->fetch_assoc()) {
            $output[] = $row;
        }

        echo json_encode($output);
    } else {
        // Tratar erros de consulta SQL
        echo json_encode(array('error' => $conn->error));
    }
}

if(isset($_POST["criar_token"])){
    $id_user=$_SESSION["id_user"];
    $id_tabuleiro=$_POST["id_tabuleiro"];
    $imagem_token=$_POST['imagem_token'];
    $sql = "INSERT INTO tokens (id_tabuleiro,x,y,id_usuario,link_foto) VALUES ($id_tabuleiro,0,0,$id_user,'$imagem_token')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $last_id = mysqli_insert_id($conn);

    $sql = "INSERT INTO recursos (id_token,nome,valor) VALUES ($last_id,'HP',100)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $sql = "INSERT INTO fichas (id_token,id_tabuleiro) VALUES ($last_id,$id_tabuleiro)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
}

if(isset($_POST["excluir_area_efeito"])){
    $sql="DELETE FROM area_efeito WHERE id=".$_POST["id_area_effect"];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

if(isset($_POST["criar_tabuleiro"])){
    $nome=$_POST["nome_tabuleiro"];
    $usuario_criador=$_SESSION["id_user"];

    try {
        $sql = "INSERT INTO tabuleiros (largura, altura, link_foto, nome, mostrar_grid) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        $largura = 25;
        $altura = 15;
        $link_foto = "https://images.squarespace-cdn.com/content/v1/62750da488ff573e9bdc63c4/4df33641-227f-49bc-8081-81322c4d1273/TC_The+Warty+Hag+02+Tavern+Night_No+Grid_17x11.jpg";
        $mostrar_grid = "s";

        $stmt->bind_param('iisss', $largura, $altura, $link_foto, $nome, $mostrar_grid);
        
        $stmt->execute();
        $id_sala = $conn->insert_id;

        
        $query = 'INSERT INTO usuario_tabuleiro (id_user, id_tabuleiro , eh_admin)
        VALUES ('.$usuario_criador.','.$id_sala.',"s");';
        $stmt = $conn->prepare($query);
        $stmt->execute();

    } catch (PDOException $e) {
        // Lide com erros, se necessário
        die("Error: " . $e->getMessage());
    }

}

if(isset($_POST["criar_area_efeito"])){
    $id_tabuleiro=$_POST["id_tabuleiro"];
    $altura=$_POST["altura"];
    $largura=$_POST["largura"];
    $id_user=$_SESSION["id_user"];
    $x=$_POST["x"];
    $y=$_POST["y"];
    $cor=$_POST["cor"];
    
    if($cor==""){
        $cor="#000000";
    }

    $sql = "INSERT INTO area_efeito (altura,largura,id_tabuleiro,id_user,x,y,cor) VALUES ($altura,$largura,$id_tabuleiro,$id_user,$x,$y,'$cor')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

if(isset($_POST["query_tabuleiros"])){
    try {
        $query = 'select * from usuario_tabuleiro
        INNER JOIN tabuleiros ON tabuleiros.id=usuario_tabuleiro.id_tabuleiro
        WHERE id_user='.$_SESSION["id_user"]; // Ordenar por ID de forma descendente

        $results = $conn->query($query);
    ?>
    <div class="container" style="background-color:#333">
        <div class="row" style="background-color:#333;" >
            <div style="width: 99%;">
                <?php foreach ($results as $row) { ?>
                    <div class="card mb-3" style="background-color:#333; border-radius:0px;">
                        <div class="card-body" style="background-color:#444; padding: 2%">
                        
                            <div class="row" style="margin-left:2%;">
                                    <div style="width:65%;max-width:65%;">
                                        <h5 class="card-title" style="background-color:#444; margin-top:4%; align-self: flex-start;"><?= $row["nome"] ?></h5>
                                    </div>
                                    <button data="<?php echo $row["nome"];?>" id="botao_tabuleiro_<?php echo $row["id"];?>" class="btn btn-primary botao_tabuleiro" style="width:25%; align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-left:3%; margin-top:1%;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"></path>
                                            <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"></path>
                                        </svg>
                                    </button>
                            </div>


                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    } catch (PDOException $e) {
        // Lide com erros, se necessário
        die("Error: " . $e->getMessage());
    }
}

if(isset($_GET["get_token_image"])){
    $id_token=$_GET["id_token"];

    $sql="SELECT link_foto FROM tokens WHERE id=$id_token";
    $results = $conn->query($sql);

    $data = array(); // Inicialize um array vazio para armazenar os resultados

    foreach ($results as $result) {
        $data[] = $result; // Adicione cada resultado ao array $data
    }
    
    echo json_encode($data);
}

if(isset($_POST["set_token_image"])){
    $id_token=$_POST["id_token"];
    $link_foto=$_POST["link_foto"];


    $sql="UPDATE tokens SET link_foto='$link_foto' WHERE id=$id_token";
    $results = $conn->query($sql);

    echo "Sucesso.";
}

if(isset($_POST["duplicar_token"])){
    $id_token=$_POST["id_token"];

    $sql="SELECT id,id_tabuleiro,x,y,id_usuario,link_foto FROM tokens where id=$id_token";
    $results = $conn->query($sql);

    foreach($results as $result){
        //clonando o token no tabuleiro
        $id_tabuleiro=$result['id_tabuleiro'];
        $x=$result['x'];
        $y=$result['y'];
        $id_usuario=$result['id_usuario'];
        $link_foto=$result['link_foto'];

        $sql="INSERT INTO tokens (id_tabuleiro,x,y,id_usuario,link_foto) VALUES ($id_tabuleiro,$x,$y-1,$id_usuario,'$link_foto')";
        $results = $conn->query($sql);

        $last_id = mysqli_insert_id($conn);

        //clonando os recursos
        $sql2="SELECT id,id_token,nome,valor,cor,valor_minimo,valor_maximo FROM recursos where id_token=$id_token";
        $results2 = $conn->query($sql2);
        foreach($results2 as $result2){
            $nome=$result2["nome"];
            $valor=$result2["valor"];
            $cor=$result2["cor"];
            $valor_minimo=$result2["valor_minimo"];
            $valor_maximo=$result2["valor_maximo"];

            $sql = "INSERT INTO recursos (id_token,nome,valor,cor,valor_minimo,valor_maximo) VALUES ($last_id,'$nome',$valor,'$cor',$valor_minimo,$valor_maximo)";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }

        //clonando as fichas
        $sql3="SELECT id_token,texto,id_tabuleiro FROM fichas where id_token=$id_token";
        $results3 = $conn->query($sql3);
        foreach($results3 as $result3){
            $texto=$result3["texto"];
            $id_tabuleiro=$result3["id_tabuleiro"];

            $sql = "INSERT INTO fichas (id_token,texto,id_tabuleiro) VALUES ($last_id,'$texto',$id_tabuleiro)";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }

    }
    
    

    

    echo "Sucesso.";
}

if(isset($_POST["delete_token"])){
    $id_token=$_POST["id_token"];

    $sql="DELETE FROM tokens WHERE id=$id_token";
    $results = $conn->query($sql);

    $sql="DELETE FROM recursos WHERE id_token=$id_token";
    $results = $conn->query($sql);

    $sql="DELETE FROM fichas WHERE id_token=$id_token";
    $results = $conn->query($sql);


    echo "Sucesso.";
}

if (isset($_POST["create_recurso_e_anexar_a_token"])) {
    $idtoken = $_POST["id_token"];

    try {
        $sql = "INSERT INTO recursos (id_token) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $idtoken); 
        $stmt->execute();

        echo "Recurso criado com sucesso!"; 

    } catch (mysqli_sql_exception $e) {
        error_log("Error creating recurso: " . $e->getMessage(), 0);

        echo "An error occurred while creating the recurso. Please try again later.";
    }
}

if(isset($_POST["excluir_recurso"])){
    $id_recurso=$_POST["id_recurso"];
    $sql = "DELETE FROM recursos WHERE id=$id_recurso";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

if(isset($_POST["update_valores_recurso"])){
    $id_recurso=$_POST["id_recurso"];
    $novo_valor_max=$_POST["novo_valor_max"];
    $novo_valor_min=$_POST["novo_valor_min"];
    $novo_valor_atual=$_POST["novo_valor_atual"];
    $novo_nome=$_POST["novo_nome"];
    $nova_cor=$_POST["nova_cor"];
    $sql = "UPDATE recursos SET 
                nome='$novo_nome',
                valor_maximo=$novo_valor_max,
                valor_minimo=$novo_valor_min, 
                valor=$novo_valor_atual,
                cor='$nova_cor'
                WHERE id=$id_recurso
                ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}


if(isset($_POST["get_ficha_by_token_id"])){
    $id_token = $_POST["id_token"];

    // Usando prepared statement para prevenir SQL Injection
    $stmt = $conn->prepare("SELECT * FROM fichas WHERE id_token = ?");
    $stmt->bind_param("i", $id_token);
    $stmt->execute();
    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        ?>
        <script>
            function renderizador_de_fichas(texto) {
                var conteudoDiv = $('<div>').html(texto);
                $('#div_ficha').append(conteudoDiv.html());
            }

            renderizador_de_fichas(<?php foreach($results as $row){ echo json_encode($row["texto"]); }?>);
        </script>
        <?php
    }
}

if(isset($_POST["get_ficha_id_by_token_id"])){
    $id_token = $_POST["id_token"];

    $stmt = $conn->prepare("SELECT fichas.id FROM fichas WHERE id_token = ?");
    $stmt->bind_param("i", $id_token);
    $stmt->execute();
    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        foreach($results as $result){
            echo json_encode($result);
        }
    } else {
        echo "Nenhuma ficha encontrada para o token fornecido.";
    }
}

if(isset($_POST["salvar_ficha"])){

    $id_ficha=$_POST["id_ficha"];
    $texto=$_POST["texto"];

    $sql = "UPDATE fichas SET texto=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $texto, $id_ficha);
    $stmt->execute();
}

if(isset($_POST["get_textbox_ficha"])){
    $id_ficha = $_POST["id_ficha"];

    // Usando prepared statement para prevenir SQL Injection
    $stmt = $conn->prepare("SELECT * FROM fichas WHERE id = ?");
    $stmt->bind_param("i", $id_ficha);
    $stmt->execute();
    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        foreach($results as $row){?>
            <textarea type="text" class="form-control" id="textbox_edicao_ficha" style="height:100%;background-color:#333;color:white;border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem;"><?=$row["texto"]?></textarea>
            
            <script>
                var $textarea = $('#textbox_edicao_ficha');
                // Adicionando um ouvinte de evento para o pressionamento de tecla
                $textarea.on('keydown', function(e) {
                // Verifica se a tecla pressionada é a tecla TAB
                if (e.key === 'Tab') {
                    // Previne o comportamento padrão da tecla TAB
                    e.preventDefault();

                    // Insere o caractere de tabulação no cursor atual
                    var start = this.selectionStart;
                    var end = this.selectionEnd;

                    // Insere a tabulação na posição atual do cursor
                    $(this).val(function(_, val) {
                    return val.substring(0, start) + '\t' + val.substring(end);
                    });

                    // Move o cursor para a posição após a tabulação inserida
                    this.selectionStart = this.selectionEnd = start + 1;
                }
                });
            </script>
        
        <?php
        }
    } else {
        echo "Nenhuma ficha encontrada para o token fornecido.";
    }
}


if(isset($_GET["query_recursos"])){
    $id_tabuleiro=$_GET["id_tabuleiro"];
    $sql="SELECT tokens.id as tokenid, tokens.link_foto
            FROM tokens
            INNER JOIN tabuleiros ON tabuleiros.id=tokens.id_tabuleiro
            WHERE id_tabuleiro=$id_tabuleiro";
    $results = $conn->query($sql);

    ?>

    <style>
        #editWindow {
        display: none;
        position: absolute;
        top: 0;
        left: calc(100% + 10px); /* Posiciona ao lado da barra de progresso */
        width: 200px;
        padding: 10px;
        background-color: #333;
        border: 1px solid white;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    </style>



    <!--FUNÇÕES DE BARRA-->
    <script>
       function query_recursos(){
            $.ajax({
                type: 'GET',
                url: './../api/tabuleiro.api.php',
                data: {
                    query_recursos:'S',
                    id_tabuleiro:id_tabuleiro_atual
                },
                dataType: 'html',
                success: function(data) {
                    $("#query-recursos").empty().append(data);

                },
                error: function(xhr, status, error) {
                    console.log("Erro na solicitação AJAX: " + status + " - " + error);
                } 
            });
        }

        
        $('.button_add_recurso').on('click', function() {
            var id_token=$(this).attr("id_token");
            $.ajax({
                type: 'POST',
                url: './../api/tabuleiro.api.php',
                data: {
                    create_recurso_e_anexar_a_token:'S',
                    id_token:id_token
                },
                dataType: 'text',
                success: function(data) {
                    toastr.success("Recurso criado com sucesso.","Sucesso!");
                    query_recursos();
                },
                error: function(xhr, status, error) {
                    console.log("Erro na solicitação AJAX: " + status + " - " + error);
                } 
            });
            
        });

        
        $('.button_excluir_recurso').on('click', function() {
            var id_recurso=$(this).attr("id_recurso");
            $.ajax({
                type: 'POST',
                url: './../api/tabuleiro.api.php',
                data: {
                    excluir_recurso:'S',
                    id_recurso:id_recurso
                },
                dataType: 'text',
                success: function(data) {
                    toastr.success("Recurso Excluído com sucesso.","Sucesso!");
                    query_recursos();
                },
                error: function(xhr, status, error) {
                    console.log("Erro na solicitação AJAX: " + status + " - " + error);
                } 
            });
        });

        $('.progress_abrir').on('click', function() {
            var id_recurso=$(this).attr("id_recurso");
            $('#edit_window_' + id_recurso).slideToggle("fast");
        });

        $('.input_barra_valoratual').on('change', function() {
            var id_recurso=$(this).attr("id_recurso");
            var novo_valor = $(this).val(); 
            sync_barra_progress( id_recurso , novo_valor );
            update_valores_recurso(id_recurso);
        });
        $('.input_barra_valormin').on('change', function() {
            var id_recurso=$(this).attr("id_recurso");
            var novo_valor = $("#input_value_resource_"+id_recurso).val();
            $("#recurso_"+id_recurso).attr("aria-valuemin",$(this).val());
            sync_barra_progress( id_recurso , novo_valor );
            update_valores_recurso(id_recurso);
        });
        $('.input_barra_valormax').on('change', function() {
            var id_recurso=$(this).attr("id_recurso");
            var novo_valor = $("#input_value_resource_"+id_recurso).val();
            $("#recurso_"+id_recurso).attr("aria-valuemax",$(this).val());
            sync_barra_progress( id_recurso , novo_valor );
            update_valores_recurso(id_recurso);
        });
        $('.input_barra_nome').on('change', function() {
            var id_recurso=$(this).attr("id_recurso");
            var novo_valor = $("#input_value_resource_"+id_recurso).val();
            $("#recurso_"+id_recurso).attr("nome",$(this).val());
            sync_barra_progress( id_recurso , novo_valor );
            update_valores_recurso(id_recurso);
        });

        $('.color_picker_recurso').on('change', function() {
            var id_recurso=$(this).attr("id_recurso");
            $("#recurso_"+id_recurso).css("background-color",$(this).val());
            update_valores_recurso(id_recurso);
        });

        function update_valores_recurso(id_recurso){
            novo_valor_max=$("#recurso_"+id_recurso).attr("aria-valuemax") ;
            novo_valor_min=$("#recurso_"+id_recurso).attr("aria-valuemin") ;
            novo_valor_atual=$("#input_value_resource_"+id_recurso).val();
            novo_nome=$("#recurso_"+id_recurso).attr("nome");
            nova_cor=$("#colorPicker_recurso_"+id_recurso).val();

            $.ajax({
                type: 'POST',
                url: './../api/tabuleiro.api.php',
                data: {
                    update_valores_recurso:'S',
                    id_recurso:id_recurso,
                    novo_valor_max:novo_valor_max,
                    novo_valor_min:novo_valor_min,
                    novo_valor_atual:novo_valor_atual,
                    novo_nome:novo_nome,
                    nova_cor:nova_cor
                },
                dataType: 'html',
                success: function(data) {

                },
                error: function(xhr, status, error) {
                    console.log("Erro na solicitação AJAX: " + status + " - " + error);
                } 
            });
        }

        

        
        function sync_barra_progress(barra_id,novo_valor){
            //console.log("Valor antigo:"+$("#recurso_"+barra_id).attr("aria-valuenow"));

            $("#recurso_"+barra_id).attr("aria-valuenow",novo_valor);
            var valor_atual=$("#recurso_"+barra_id).attr("aria-valuenow");
            var valor_min=$("#recurso_"+barra_id).attr("aria-valuemin") ;
            var valor_max=$("#recurso_"+barra_id).attr("aria-valuemax") ;
            var nome=$("#recurso_"+barra_id).attr("nome");
            

            //console.log("Valor novo:"+novo_valor);
            //console.log("Valor minimo:"+valor_min);
            //console.log("Valor maximo:"+valor_max);

            completude = ((valor_atual - valor_min) / (valor_max - valor_min)) * 100;
            
            console.log("completude:"+completude);

            $("#recurso_"+barra_id).css("width",completude+"%");
            $("#valoratual_valormax_"+barra_id).empty().append("<label style='margin-top:10px;' id='valoratual_valormax_"+barra_id+"'>"+nome+" - "+valor_atual+"/"+valor_max+"</label>")
        }
        $(document).ready(function(){
            $('.color_picker_recurso').colorpicker();
        });



    </script>


    <div class="scrollable" id="#scrollable_div1" style="padding:0;">


        <?php
        foreach($results as $row){?>

        
            <div class="row" style="margin-top:2px;background-color:#333;width:100%;height:30%;margin-left:0">
                <img class="token" id="img_t_<?=$row["tokenid"]?>" src="<?=$row["link_foto"]?>" style="border:1px solid white; width:35%;height:100%;border-radius:0;">
            

                <?php
                    $sql2="SELECT fichas.id as idficha,tokens.id as tokenid, tokens.link_foto, recursos.id as idrecurso, recursos.id_token, recursos.id_ficha,recursos.nome,recursos.valor,recursos.cor,recursos.valor_minimo,recursos.valor_maximo
                    FROM tokens
                    INNER JOIN recursos ON recursos.id_token=tokens.id
                    INNER JOIN fichas ON tokens.id=fichas.id_token
                    WHERE tokens.id=".$row["tokenid"];
                    $results2 = $conn->query($sql2);
                    ?>

                    <div style="width:35%;height:100%;" class="scrollable" >
                        <?php
                            foreach($results2 as $row2){
                                $valormin=$row2["valor_minimo"];
                                $valormax=$row2["valor_maximo"];
                                $valoratual=$row2["valor"];
                                ?>
                                    <div class="progress progress_abrir" id_recurso="<?=$row2["idrecurso"]?>" style="border:1px solid white;background-color:#444;width:100%;height:20px;margin-top:0.3%;padding:1px;"  >
                                        <div nome="<?=$row2["nome"];?>" id="recurso_<?=$row2["idrecurso"]?>" class="progress-bar progress_abrir" role="progressbar"
                                        style="height:20px;background-color:<?=$row2["cor"]?>; width:<?=(($valoratual - $valormin) / ($valormax - $valormin)) * 100?>%;" 
                                        aria-valuenow="<?=$valoratual?>" 
                                        aria-valuemin="<?=$valormin?>" 
                                        aria-valuemax="<?=$valormax?>" 
                                        aria-label="<?=$row2["nome"];?>">
                                        
                                         <label id="valoratual_valormax_<?=$row2["idrecurso"]?>"> <?=$row2["nome"];?> - <?=$valoratual?>/<?=$valormax?></label>
                                        </div>
                                    </div>

                                    <div class="editWindow" id="edit_window_<?=$row2["idrecurso"]?>" style='display:none'>
                                        
                                        <div class="row" style="margin-left:0.005%;">
                                            <input value="<?=$row2["nome"]?>" class="form-control input_barra_nome" id_recurso="<?=$row2["idrecurso"]?>" id="input_nome_resource_<?=$row2["idrecurso"]?>" type="text"  style="padding:2px;width:54%;background-color:#444;color:white;border-radius:0px;margin-left:0.3vw; border-bottom-left-radius:0;border-bottom-right-radius:0; font-size: 8px;" placeholder="Nome">
                                            <button id_token="<?=$row["tokenid"]?>" id="button_excluir_recurso_<?=$row2["idrecurso"]?>" id_recurso="<?=$row2["idrecurso"]?>" class="btn btn-danger button_excluir_recurso" style="font-size:15px;border:1px solid white;width:30%;padding:1px;background-color:red;border-radius:0px;">X</button>
                                        </div>
                                        <input value="<?=$valoratual?>" class="form-control input_barra_valoratual" id_recurso="<?=$row2["idrecurso"]?>" id="input_value_resource_<?=$row2["idrecurso"]?>" type="number"  style="padding:2px;width:60%;background-color:#444;color:white;border-radius:0px;margin-left:0.3vw; border-bottom-left-radius:0;border-bottom-right-radius:0; font-size: 8px;" placeholder="Novo valor">
                                        <input value="<?=$valormin?>" class="form-control input_barra_valormin" id_recurso="<?=$row2["idrecurso"]?>" id="input_valuemin_resource_<?=$row2["idrecurso"]?>" type="number"  style="padding:2px;width:60%;background-color:#444;color:white;border-radius:0px;margin-left:0.3vw; border-bottom-left-radius:0;border-bottom-right-radius:0; font-size: 8px;" placeholder="Novo valor Mínimo">
                                        <input value="<?=$valormax?>" class="form-control input_barra_valormax" id_recurso="<?=$row2["idrecurso"]?>" id="input_valuemax_resource_<?=$row2["idrecurso"]?>" type="number"  style="padding:2px;width:60%;background-color:#444;color:white;border-radius:0px;margin-left:0.3vw; border-bottom-left-radius:0;border-bottom-right-radius:0; font-size: 8px;" placeholder="Novo valor Máximo">
                                        <input value="<?=$row2["cor"]?>" type="text" class="form-control color_picker_recurso" id_recurso="<?=$row2["idrecurso"]?>" id="colorPicker_recurso_<?=$row2["idrecurso"]?>" style="padding:2px;width:60%;background-color:#444;color:white;border-radius:0px;margin-left:0.3vw; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem; font-size: 8px;" placeholder="Cor da barra" readonly>
                                    </div>
                            <?php
                            }
                            ?>
                        <button id_token="<?=$row["tokenid"]?>" id="button_add_recurso_<?=$row["tokenid"]?>" class="btn btn-secondary button_add_recurso" style="font-size:12px;margin-top:1px;width:100%;padding:1px;background-color:#444">+</button>
                    </div>
                    <div style="border: 1px solid white; width:30%;height:100%;">
                        <center>
                            <button id_token="<?=$row["tokenid"]?>" id_ficha="<?=$row2["idficha"]?>"  id="button_gerenciar_recursos_<?=$row2["idrecurso"]?>" class="btn btn-secondary abrir_ficha_por_id" data-dismiss="modal" style="font-size:11px;background-color:#444;width:95%">Ficha</button>
                            <button type="button" id_token="<?=$row["tokenid"]?>" id="token_click_duplicar2" class="btn btn-secondary" style="font-size:11px;background-color:#444;width:95%">Duplicar</button>
                            <button type="button" id_token="<?=$row["tokenid"]?>" id="excluir_token_click_confirmar2" class="btn btn-secondary" style="font-size:11px;background-color:#444;width:95%">Excluir</button>
                            
                        </center>
                    </div>
            </div>
            

        <?php
        }
        ?>
    </div>

     

    </div>


    


    <?php
}


if(isset($_GET["query_modal_fichas"])){
    $id_tabuleiro=$_GET["id_tabuleiro"];
    $sql="SELECT 
    tabuleiros.id as idtabuleiro,
    texto,
    id_token,
    largura,
    altura,
    tokens.link_foto,
    nome,
    mostrar_grid,
    ultima_atualizacao,
    id_usuario
            FROM fichas
            INNER JOIN tabuleiros ON tabuleiros.id=fichas.id_tabuleiro
            INNER JOIN tokens ON fichas.id_token=tokens.id
            WHERE fichas.id_tabuleiro=$id_tabuleiro";
    $results = $conn->query($sql);

    ?>
    <script>

    $('.abrir_ficha_associada_a_token').on('click', function() {
            $("#botao_salvar_ficha").hide();
            $("#botao_editar_ficha").show();
            var id_token=$(this).attr("id_token");
            $.ajax({
                type: 'POST',
                url: './../api/tabuleiro.api.php',
                data: {
                    get_ficha_id_by_token_id:'S',
                    id_token:id_token
                },
                dataType: 'json',
                success: function(data) {
                    $("#div_ficha").attr("id_ficha",data.id);
                    $("#botao_editar_ficha").attr("id_ficha",data.id);
                    $("#botao_salvar_ficha").attr("id_ficha",data.id);

                    $.ajax({
                        type: 'POST',
                        url: './../api/tabuleiro.api.php',
                        data: {
                            get_ficha_by_token_id:'S',
                            id_token:id_token
                        },
                        dataType: 'html',
                        success: function(data) {
                            $("#div_ficha").empty().append(data);
                            $("#div_ficha").attr("id_token",id_token);
                            
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

    </script>
    <div >
        
        <div id="div_tokens" class="scrollable" style="width: 10%; float: left; margin: 0; padding: 0;">
            
            <?php foreach($results as $result) { ?>
                <img class="abrir_ficha_associada_a_token" id_token="<?=$result["id_token"]?>"id="img_r_<?=$result["id_token"]?>" src="<?=$result["link_foto"]?>" style="border: 1px solid white; height: auto; width: 100%; margin: 0; cursor:pointer;border-radius: 0;">
            <?php } ?>
        
        </div>

        <div id="div_ficha"  class="scrollable"style="width: 90%; height: 70vh ;float:right;  background-color: #333; border: 1px solid white;">
            <!-- Conteúdo da div "div_ficha" -->

        </div>

    </div>


    <?php
}

if(isset($_POST["get_nota"])){
    $stmt = $conn->prepare(
    "SELECT 
    texto,id_user,titulo,id
            FROM nota
            WHERE id=".$_POST["id_nota"]);
    $stmt->execute();
    $results = $stmt->get_result();
    
    if($results->num_rows > 0) {
        ?>
        <script>
            function renderizador_de_notas(texto) {
                var conteudoDiv = $('<div>').html(texto);
                $('#div_notas').append(conteudoDiv.html());
            }

            renderizador_de_notas(<?php foreach($results as $row){ echo json_encode($row["texto"]); }?>);
        </script>
        <?php
    }
}



if(isset($_POST["query_notas"])){
    $sql="SELECT 
    texto,id_user,titulo,id
            FROM nota
            WHERE id_user=".$_SESSION["id_user"];
    $results = $conn->query($sql);

    ?>
    <script>

    $('.abrir_nota').on('click', function() {
        
        $("#botao_editar_nota").css("display","block");
        
        $("#botao_excluir_nota").css("display","block");
        
        var id_nota=$(this).attr("id_nota");
            $.ajax({
                type: 'POST',
                url: './../api/tabuleiro.api.php',
                data: {
                    get_nota:"s",
                    id_nota:$(this).attr("id_nota")
                },
                dataType: 'html',
                success: function(data) {
                    $("#div_notas").empty().append(data);
                    $("#div_notas").attr("id_nota",id_nota);
                    $("#botao_editar_nota").attr("id_nota",id_nota);
                    $("#botao_salvar_nota").attr("id_nota",id_nota);
                    $("#botao_adicionar_nota").attr("id_nota",id_nota);
                    $("#botao_excluir_nota").attr("id_nota",id_nota);

                },
                error: function(xhr, status, error) {
                    console.log("Erro na solicitação AJAX: " + status + " - " + error);
                } 
            });

            
            
        });

        $('.input_titulo_nota').on('change', function() {
            var id_nota=$(this).attr("id_nota");
            var titulo=$(this).val();
            $.ajax({
                type: 'POST',
                url: './../api/tabuleiro.api.php',
                data: {
                    update_titulo_nota:"s",
                    id_nota:id_nota,
                    titulo:titulo
                },
                dataType: 'html',
                success: function(data) {

                },
                error: function(xhr, status, error) {
                    console.log("Erro na solicitação AJAX: " + status + " - " + error);
                } 
            });
        });

        
    </script>
    <div >
        
        <div id="div_titulos" class="scrollable" style="width: 20%; float: left; margin: 0; padding: 0;">
            
            <div class="container scrollable" style="background-color:#333; height:100%;">

                    <?php foreach ($results as $row) { ?>
                        <div class="card mb-3" style="background-color:#333; border-radius:0px;">
                            <div class="card-body" style="background-color:#444; padding: 2%">
                            
                                <div class="row" style="margin-left:2%;">
                                        <!--
                                        <div style="width:65%;max-width:65%;">
                                            <h5 class="card-title" style="background-color:#444; margin-top:4%; align-self: flex-start;"><?= $row["titulo"] ?></h5>
                                        </div>
                                        -->
                                        <input type="text" id_nota="<?=$row["id"]?>" id="nota_<?=$row["id"]?>" class="form-control input_titulo_nota" style="background-color:#333;color:white; border-radius:0px; border-bottom-left-radius:0.25rem;width:80%;" placeholder="Digite o nome da sua Nota" value="<?=$row["titulo"]?>">

                                        <button id_nota="<?php echo $row["id"];?>" data="<?php echo $row["titulo"];?>" id="botao_nota_<?php echo $row["id"];?>" class="btn btn-primary abrir_nota" style="width:10%; min-width:16px; padding:0;align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-left:2%; margin-top:1%;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"></path>
                                                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"></path>
                                            </svg>
                                        </button>
                                        
                                        
                                </div>


                            </div>
                        </div>
                    <?php } ?>

            </div>
            
        </div>

        <div id="div_notas" id_nota="0"  class="scrollable"style="width: 80%; height: 70vh ;float:right;  background-color: #333; border: 1px solid white;">
            <!-- Conteúdo da div "div_notas" -->

        </div>

    </div>


    <?php
}


if(isset($_POST["create_nota"])){
    try {
        $sql = "INSERT INTO nota (id_user) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION["id_user"]); 
        $stmt->execute();

        echo "Nota criada com sucesso!"; 

    } catch (mysqli_sql_exception $e) {
        error_log("Error creating nota: " . $e->getMessage(), 0);

        echo "An error occurred while creating the nota. Please try again later.";
    }
}


if(isset($_POST["editar_nota"])){
    $id_nota=$_POST["id_nota"];

    $sql="SELECT id,id_user,texto,titulo FROM nota WHERE id=$id_nota";
    $results = $conn->query($sql);
    foreach ($results as $row){
        ?>
        <textarea type="text" class="form-control" id="textbox_edicao_nota" style="height:100%;background-color:#333;color:white;border-radius:0px; border-bottom-left-radius:0.25rem;border-bottom-right-radius:0.25rem;"><?=$row["texto"]?></textarea>
            
            <script>
                var $textarea = $('#textbox_edicao_nota');
                // Adicionando um ouvinte de evento para o pressionamento de tecla
                $textarea.on('keydown', function(e) {
                // Verifica se a tecla pressionada é a tecla TAB
                if (e.key === 'Tab') {
                    // Previne o comportamento padrão da tecla TAB
                    e.preventDefault();

                    // Insere o caractere de tabulação no cursor atual
                    var start = this.selectionStart;
                    var end = this.selectionEnd;

                    // Insere a tabulação na posição atual do cursor
                    $(this).val(function(_, val) {
                    return val.substring(0, start) + '\t' + val.substring(end);
                    });

                    // Move o cursor para a posição após a tabulação inserida
                    this.selectionStart = this.selectionEnd = start + 1;
                }
                });
            </script>
        <?php
    }

}


if(isset($_POST["salvar_nota"])){
    $id_nota = $_POST["id_nota"];
    $texto = $_POST["texto"];
    
    $sql = "UPDATE nota SET texto=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $texto, $id_nota);
    $stmt->execute(); // Execute o statement preparado
    
    if ($stmt->affected_rows > 0) {
        // A atualização foi bem-sucedida
    } else {
        // A atualização falhou ou nenhum registro foi afetado
    }
    $stmt->close(); // Feche o statement após o uso
    
}

if(isset($_POST["update_titulo_nota"])){
    $id_nota=$_POST["id_nota"];
    $titulo=$_POST["titulo"];

    $sql="UPDATE nota SET titulo='$titulo' WHERE id=$id_nota";
    $results = $conn->query($sql);
}


if(isset($_POST["delete_nota"])){
    $id_nota=$_POST["id_nota"];

    $sql="DELETE FROM nota WHERE id=$id_nota";
    $results = $conn->query($sql);
}

?>


