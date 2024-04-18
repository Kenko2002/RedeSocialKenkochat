<?php
include "conexao.php";
session_start(); 
?>


<?php
#QUERYCHAT
if(isset($_POST["get_nome_sala"])){
    $sql="SELECT nome FROM sala WHERE id=".$_POST["id_sala"];
    $results = $conn->query($sql);

    foreach ($results as $row) {
        ?> <h4> <?=$row["nome"]?> </h4> <?php
    }
}

if(isset($_POST["set_nome_sala"])){
    if($_SESSION["eh_admin"]){
        $sql="UPDATE sala SET nome='".$_POST["novo_nome"]."' WHERE id=".$_POST["id_sala"];
        $results = $conn->query($sql);
        if($results){
            echo json_encode("Nome da sala atualizado com sucesso!");
        } else {
            echo json_encode("Erro ao atualizar o nome da sala.");
        }
    } else {
        echo json_encode("Você não tem permissão para realizar esta ação.");
    }
}

if(isset($_POST["set_musica_sala"])){
    if($_SESSION["eh_admin"]){
        $sql="UPDATE sala SET musica='".$_POST["link_novo_tema"]."' WHERE id=".$_POST["id_sala"];
        $results = $conn->query($sql);
    }else{
        echo json_encode("Erro!");
    }
}

if(isset($_POST["get_all_friends_id_by_id"])){
    $id_user=strval($_SESSION["id_user"]);
    $sql="SELECT * FROM amizade WHERE id_user1 = $id_user OR id_user2 = $id_user";
    $results = $conn->query($sql);
    
    //var_dump($id_user);

    $lista_id_amigos = [0];
    foreach ($results as $row) {
        //var_dump($row);
        if ($row["id_user1"] == $id_user) {
            array_push($lista_id_amigos, $row["id_user2"]);
        }
        if ($row["id_user2"] == $id_user) {
            array_push($lista_id_amigos, $row["id_user1"]);
        }
    }
    echo json_encode($lista_id_amigos);
}

if(isset($_POST["remover_relacionamento_pessoa_sala"])){
    $id_sala=$_POST["id_sala"];
    $id_pessoa=$_POST["id_pessoa"];

    $sql="DELETE FROM sala_usuarios WHERE id_user=".$id_pessoa." AND id_sala=".$id_sala;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

}

if(isset($_POST["get_musica_sala"])){
    $id_sala=$_POST["id_sala"];
    $sql="SELECT * FROM sala WHERE id=".$id_sala;
    $results = $conn->query($sql);
    foreach($results as $row){
        echo json_encode($row["musica"]);
    }
}

if(isset($_POST["abrir_info_sala"])){
    $sql="SELECT usuarios.nome as nome_user,usuarios.id as id_user , sala_usuarios.eh_admin , usuarios.link_foto, sala.musica as salamusica
    FROM sala 
    JOIN sala_usuarios ON sala.id=sala_usuarios.id_sala
    JOIN usuarios ON usuarios.id=sala_usuarios.id_user
    WHERE sala.id=".$_POST["id_sala"]."
    GROUP BY usuarios.id";
    $results = $conn->query($sql);
    ?>
        <?php foreach ($results as $row) { ?>
                <tr >
                    <td> 
                        <div class="row">
                        <img style="width:4vw;height:8vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                        <h5 class="card-title" style="background-color:#333; margin-top:5%;margin-left:5%; border-color:white"><?=$row["nome_user"] ?></h5> 
                        <?php if($row["eh_admin"]=="s"){?>   
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cup-hot-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M.5 6a.5.5 0 0 0-.488.608l1.652 7.434A2.5 2.5 0 0 0 4.104 16h5.792a2.5 2.5 0 0 0 2.44-1.958l.131-.59a3 3 0 0 0 1.3-5.854l.221-.99A.5.5 0 0 0 13.5 6zM13 12.5a2 2 0 0 1-.316-.025l.867-3.898A2.001 2.001 0 0 1 13 12.5"/>
                                <path d="m4.4.8-.003.004-.014.019a4 4 0 0 0-.204.31 2 2 0 0 0-.141.267c-.026.06-.034.092-.037.103v.004a.6.6 0 0 0 .091.248c.075.133.178.272.308.445l.01.012c.118.158.26.347.37.543.112.2.22.455.22.745 0 .188-.065.368-.119.494a3 3 0 0 1-.202.388 5 5 0 0 1-.253.382l-.018.025-.005.008-.002.002A.5.5 0 0 1 3.6 4.2l.003-.004.014-.019a4 4 0 0 0 .204-.31 2 2 0 0 0 .141-.267c.026-.06.034-.092.037-.103a.6.6 0 0 0-.09-.252A4 4 0 0 0 3.6 2.8l-.01-.012a5 5 0 0 1-.37-.543A1.53 1.53 0 0 1 3 1.5c0-.188.065-.368.119-.494.059-.138.134-.274.202-.388a6 6 0 0 1 .253-.382l.025-.035A.5.5 0 0 1 4.4.8m3 0-.003.004-.014.019a4 4 0 0 0-.204.31 2 2 0 0 0-.141.267c-.026.06-.034.092-.037.103v.004a.6.6 0 0 0 .091.248c.075.133.178.272.308.445l.01.012c.118.158.26.347.37.543.112.2.22.455.22.745 0 .188-.065.368-.119.494a3 3 0 0 1-.202.388 5 5 0 0 1-.253.382l-.018.025-.005.008-.002.002A.5.5 0 0 1 6.6 4.2l.003-.004.014-.019a4 4 0 0 0 .204-.31 2 2 0 0 0 .141-.267c.026-.06.034-.092.037-.103a.6.6 0 0 0-.09-.252A4 4 0 0 0 6.6 2.8l-.01-.012a5 5 0 0 1-.37-.543A1.53 1.53 0 0 1 6 1.5c0-.188.065-.368.119-.494.059-.138.134-.274.202-.388a6 6 0 0 1 .253-.382l.025-.035A.5.5 0 0 1 7.4.8m3 0-.003.004-.014.019a4 4 0 0 0-.204.31 2 2 0 0 0-.141.267c-.026.06-.034.092-.037.103v.004a.6.6 0 0 0 .091.248c.075.133.178.272.308.445l.01.012c.118.158.26.347.37.543.112.2.22.455.22.745 0 .188-.065.368-.119.494a3 3 0 0 1-.202.388 5 5 0 0 1-.252.382l-.019.025-.005.008-.002.002A.5.5 0 0 1 9.6 4.2l.003-.004.014-.019a4 4 0 0 0 .204-.31 2 2 0 0 0 .141-.267c.026-.06.034-.092.037-.103a.6.6 0 0 0-.09-.252A4 4 0 0 0 9.6 2.8l-.01-.012a5 5 0 0 1-.37-.543A1.53 1.53 0 0 1 9 1.5c0-.188.065-.368.119-.494.059-.138.134-.274.202-.388a6 6 0 0 1 .253-.382l.025-.035A.5.5 0 0 1 10.4.8"/>
                            </svg>
                        <?php } ?>  
                        </div>  
                    </td>

                    <td> 
                        <?php if($_SESSION["eh_admin"]=="s"){?>
                        <button class="btn btn-sucess remover_pessoa" style="border-color:white;color:white;margin-left:30%" id="user_kick_<?=$row["id_user"]?>" data-dismiss="modal">     
                        Remover</button> 
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
    </div>
    
<?php
}
if(isset($_POST["criar_relacao_pessoa_sala_nao_admin"])){
    try {
        $query = 'INSERT INTO sala_usuarios (id_user, id_sala , eh_admin)
        VALUES ('.$_POST["id_pessoa"].','.$_POST["id_sala"].',"n");'; // Ordenar por ID de forma descendente
        $stmt = $conn->prepare($query);
        $stmt->execute();

    } catch (PDOException $e) {
        // Lide com erros, se necessário
        die("Error: " . $e->getMessage());
    }
}

if(isset($_POST["criar_relacao_pessoa_tabuleiro_nao_admin"])){
    try {
        $query = 'INSERT INTO usuario_tabuleiro (id_user, id_tabuleiro , eh_admin)
        VALUES ('.$_POST["id_pessoa"].','.$_POST["id_tabuleiro"].',"n");'; // Ordenar por ID de forma descendente
        $stmt = $conn->prepare($query);
        $stmt->execute();

    } catch (PDOException $e) {
        // Lide com erros, se necessário
        die("Error: " . $e->getMessage());
    }
}



if (isset($_POST["get_all_users"])) {
    $input = $_POST["input"];
    try {
        if ($input == "") {
            $query = 'SELECT * FROM usuarios WHERE usuarios.id != ' . $_SESSION["id_user"];
        } else {
            $query = 'SELECT * FROM usuarios WHERE usuarios.nome LIKE "%' . $input . '%" AND usuarios.id != ' . $_SESSION["id_user"];
        }

        $results = $conn->query($query);
    ?>
            <?php foreach ($results as $row) {
                if ($_POST["contexto"] == "adicionar_pessoa_ao_chat") { ?>

                    <tr>
                        <td>
                        <div class="row">
                            <img style="margin-left:5%;width:4vw;height:8vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                            <h5 class="card-title" style="background-color:#333; margin-top:5%;margin-left:5%; border-color:white"><?=$row["nome"] ?></h5> 
                        </div>
                        </td>

                        <?php if ($_SESSION["eh_admin"] == "s") { ?>
                            <td><button class="btn btn-sucess adicionar_pessoa" style="margin-left:30%;border-color:white;color:white;" id="user_<?= $row["id"] ?>" data-dismiss="modal">Adicionar</button></td>
                        <?php } else { ?>
                            <td>
                                <p>Sem Permissão!</p>
                            </td>
                        <?php } ?>

                    </tr>

                <?php }
                if ($_POST["contexto"] == "adicionar_amigo") {
                ?>

                    <?php if(isset($_POST["lista_id_amigos"])){ ?>
                    <tr>
                        <td>
                            <div class="row">
                                <img style="margin-left:5%;width:4vw;height:8vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                                <h5 class="card-title" style="background-color:#333; margin-top:5%;margin-left:5%; border-color:white"><?=$row["nome"] ?></h5> 
                            </div>
                        </td>

                        <?php
                         if (in_array($row["id"], $_POST["lista_id_amigos"])) { ?>
                            <td><button class="btn btn-sucess adicionar_amizade" style="margin-left:30%;border-color:white;color:white;" id="user_<?= $row["id"] ?>" data-dismiss="modal" disabled>Adicionar</button></td>
                        <?php } else { ?>
                            <td><button class="btn btn-sucess adicionar_amizade" style="margin-left:30%;border-color:white;color:white;" id="user_<?= $row["id"] ?>" data-dismiss="modal">Adicionar</button></td>
                        <?php } ?>

                        <?php }?>
                    </tr>
            <?php }?>
            <?php
                if ($_POST["contexto"] == "adicionar_pessoa_ao_tabuleiro") {
                ?>

                    <tr>
                        <td>
                        <div class="row">
                            <img style="margin-left:5%;width:4vw;height:8vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                            <h5 class="card-title" style="background-color:#333; margin-top:5%;margin-left:5%; border-color:white"><?=$row["nome"] ?></h5> 
                        </div>
                        </td>

                        <?php if ($_SESSION["eh_admin"] == "s") { ?>
                            <td><button class="btn btn-sucess adicionar_pessoa_tabuleiro" style="margin-left:30%;border-color:white;color:white;" id="user_<?= $row["id"] ?>" data-dismiss="modal">Adicionar</button></td>
                        <?php } else { ?>
                            <td>
                                <p>Sem Permissão!</p>
                            </td>
                        <?php } ?>

                    </tr>

                <?php }?>

            <?php } ?>

        </div>

    <?php
    } catch (PDOException $e) {
        // Lide com erros, se necessário
        die("Error: " . $e->getMessage());
    }
}


if(isset($_POST["get_user_name"])){
    echo($_SESSION["user"]);
}

if(isset($_GET["get_user_id"])){
    echo($_SESSION["id_user"]);
}

if(isset($_POST["query_chat"])){
    $id_sala=$_POST["id_sala"];
    $id_user=$_SESSION["id_user"];

    //descobrindo nome da sala
    $sql1="SELECT nome FROM sala WHERE id=$id_sala";
    $results = $conn->query($sql1);
    $nomesala="Chat Geral";
    foreach($results as $result){
        $nomesala=$result["nome"];
    }
    

    //buscando as mensagens da sala
    try {
        $query = 'SELECT esc.link_foto,mens.id, mens.id_escritor,mens.id_sala, mens.texto, esc.id, esc.nome
        FROM mensagem mens
        INNER JOIN usuarios esc ON esc.id = mens.id_escritor
        WHERE mens.id_sala='.$_POST["id_sala"]."
        ORDER BY mens.id 
        "; // Ordenar por ID de forma descendente

        $results = $conn->query($query);
    ?>
    <div class="container" style="background-color:#333">
        <div class="row" style="background-color:#333;" >
            <div style="width: 100%;">
                <?php foreach ($results as $row) { ?>
                    <div class="card mb-3" style="background-color:#444; border-radius:0px;">
                        <div class="card-body" style="background-color:#444; padding: 2%">
                            
                            <div class="row">
                            <img style="margin-left:1vw;min-width:30px;min-height:30px;width:3vw;height:6vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                            <h5 class="card-title" style="background-color:#444; margin-top:1%;margin-left:5%; border-color:white"><?=$row["nome"] ?></h5> 
                            </div>
                            
                            <p class="card-text"><?= $row["texto"] ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
        $sql="SELECT * FROM usuarios INNER JOIN sala_usuarios ON usuarios.id=sala_usuarios.id_user WHERE usuarios.id=".$_SESSION["id_user"]." AND sala_usuarios.id_sala=".$_POST["id_sala"];
        $results = $conn->query($sql);
        $switch=false;
        foreach($results as $row){
            if ($row["eh_admin"]=="s"){
                $_SESSION["eh_admin"]=true;
                $switch=true;
            }
        }
        if($switch==false){
            $_SESSION["eh_admin"]=false;
        }

    } catch (PDOException $e) {
        // Lide com erros, se necessário
        die("Error: " . $e->getMessage());
    }

    //atualizando o last_seen
    $sql4="UPDATE sala_usuarios SET last_seen = NOW() WHERE id_user=".$_SESSION["id_user"]." AND id_sala=".$_POST["id_sala"];
    $stmt = $conn->prepare($sql4);
    $stmt->execute();
}

if(isset($_POST["query_salas"])){
    try {
        $query = 'select * from sala_usuarios 
        INNER JOIN sala ON sala.id=sala_usuarios.id_sala
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
                                    <button data="<?php echo $row["nome"];?>" id="botao_sala_<?php echo $row["id"];?>" class="btn btn-primary botao_sala" style="width:25%; align-self: flex-end; height: 38px; background-color:#444; border-width:0px ; border-radius:0px; margin-left:3%; margin-top:1%;">
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

if(isset($_POST["enviar_mensagem"])){
    $id_user = $_POST["id_user"];
    $mensagem = $_POST["mensagem"];
    $id_sala= $_POST["id_sala"];
    
    if($mensagem=="/cls" && $_SESSION["eh_admin"]==true){
        $sql = "DELETE FROM mensagem WHERE id_sala=".$id_sala;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    else{
        if($mensagem=="/deleteroom" && $_SESSION["eh_admin"]==true){
            $sql = "DELETE FROM sala WHERE id=".$id_sala;
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $sql = "DELETE FROM mensagem WHERE id_sala=".$id_sala;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        else{
            if($mensagem=="/leaveroom" && $id_sala!=1){
                if ($id_sala!=1){
                    $sql = "DELETE FROM sala_usuarios WHERE id_sala=$id_sala AND id_user=$id_user";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    exit();
                }
            }   
            else{
                try {
                    // Use prepared statements para evitar SQL injection
                    $sql = "INSERT INTO mensagem (id_escritor, texto, id_sala) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('isi', $id_user, $mensagem, $id_sala); // Assuming $id_user is an integer
                    $stmt->execute();
                    
                    // Você não precisa retornar nenhuma resposta em JSON neste caso
                } catch (PDOException $e) {
                    // Lide com erros, se necessário
                    die("Error: " . $e->getMessage());
                }
                    //descobrindo nome da sala
                    $sql1="SELECT nome FROM sala WHERE id=$id_sala";
                    $results = $conn->query($sql1);
                    $nomesala="Chat Geral";
                    foreach($results as $result){
                        $nomesala=$result["nome"];
                    }


                    //criando notificação para todos os usuários daquela sala.
                    $sql2="SELECT id_user FROM sala_usuarios WHERE id_sala=$id_sala";
                    $results = $conn->query($sql2);
                    $lista_id_notificados=[];
                    foreach($results as $result){
                        $lista_id_notificados[] = $result["id_user"];
                    }


                    
            }
        }
    }
}

if(isset($_POST["criar_sala"])){
        $nome=$_POST["nome_sala"];
        $usuario_criador=$_SESSION["id_user"];

        try {
            // Use prepared statements para evitar SQL injection
            $sql = "INSERT INTO sala (nome,musica) VALUES (?,'https://www.youtube.com/embed/dQw4w9WgXcQ?si=CZ2Soi-e4DIiTV67')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s',$nome);
            $stmt->execute();

            $id_sala = $conn->insert_id;
            
            $query = 'INSERT INTO sala_usuarios (id_user, id_sala , eh_admin)
            VALUES ('.$usuario_criador.','.$id_sala.',"s");';
            $stmt = $conn->prepare($query);
            $stmt->execute();

            //ao passar um id_user_alvo, ele é adicionado na sala que acabou de criar.
            if(isset($_POST["id_user_alvo"])){
                try {
                    $query = 'INSERT INTO sala_usuarios (id_user, id_sala , eh_admin)
                    VALUES ('.$_POST["id_user_alvo"].','.$id_sala.',"n");'; 
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
            
                } catch (PDOException $e) {
                    // Lide com erros, se necessário
                    die("Error: " . $e->getMessage());
                }
            }

        } catch (PDOException $e) {
            // Lide com erros, se necessário
            die("Error: " . $e->getMessage());
        }

        

    }


?>