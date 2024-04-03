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
        $id_tabuleiro=$result['id_tabuleiro'];
        $x=$result['x'];
        $y=$result['y'];
        $id_usuario=$result['id_usuario'];
        $link_foto=$result['link_foto'];

        $sql="INSERT INTO tokens (id_tabuleiro,x,y,id_usuario,link_foto) VALUES ($id_tabuleiro,$x,$y-1,$id_usuario,'$link_foto')";
        $results = $conn->query($sql);
    }

    echo "Sucesso.";
}

if(isset($_POST["delete_token"])){
    $id_token=$_POST["id_token"];

    $sql="DELETE FROM tokens WHERE id=$id_token";
    $results = $conn->query($sql);

    echo "Sucesso.";
}

?>