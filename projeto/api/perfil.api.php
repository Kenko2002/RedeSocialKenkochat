<?php
include "conexao.php";
session_start(); 
?>

<?php


if(isset($_POST["excluir_amizade"])){
    $id_amizade=$_POST["id_amizade"];
    $sql="DELETE FROM amizade WHERE id=".$id_amizade;
    $results = $conn->query($sql);

    echo("Amizade Desfeita com Sucesso!");
}

if(isset($_POST["getall_amigos"])){
    $id_user=$_SESSION["id_user"];
    $sql="SELECT u1.id as u1id, u2.id as u2id, u1.nome as nome_user1, u2.nome as nome_user2, amizade.id as amizade_id, u1.link_foto as u1linkfoto, u2.link_foto as u2linkfoto
    FROM amizade 
    INNER JOIN usuarios AS u1 ON u1.id = amizade.id_user1
    INNER JOIN usuarios AS u2 ON u2.id = amizade.id_user2
    WHERE (amizade.id_user1 = ".$id_user." OR amizade.id_user2 = ".$id_user.")
    ";
    $results = $conn->query($sql);
    foreach($results as $row){
        ?>

            <?php if($_SESSION["user"]==$row["nome_user1"]){?>
                    <tr>
                        <td> 
                            <div class="row">
                                <img style="margin-left:1vw;width:4vw;height:8vh;border-radius:50%" src="<?= $row["u2linkfoto"] ?>" >
                                
                            </div>
                        </td>
                        <td> <h5 class="card-title" style="max-width:4vw;background-color:#333; border-color:white"><?= $row["nome_user2"] ?></h5>  </td>
                        <td> <button class="btn btn-sucess abrir_chat_privado" data-dismiss="modal" aria-label="Close" style="margin-left:7vw;border-color:white;color:white;" data-nome="<?=$row["nome_user2"]?>" id="chat_privado_<?= $row["u2id"]?>" >Privado</button> </td>
                        <td> <button class="btn btn-sucess excluir_amizade" data-dismiss="modal" aria-label="Close" style="margin-left:1vw;border-color:white;color:white;" id="exclude_<?= $row["amizade_id"]?>" >Excluir</button> </td>

                    </tr>
            <?php } ?>

            <?php if($_SESSION["user"]==$row["nome_user2"]){?>
                    <tr>
                        <td> 
                            <div class="row">
                                <img style="margin-left:1vw;width:4vw;height:8vh;border-radius:50%" src="<?= $row["u1linkfoto"] ?>" >
                                
                            </div>
                        </td>
                        <td> <h5 class="card-title" style="max-width:4vw;background-color:#333; border-color:white"><?= $row["nome_user1"] ?></h5>  </td>
                        <td> <button class="btn btn-sucess abrir_chat_privado" data-dismiss="modal" aria-label="Close" style="margin-left:7vw;border-color:white;color:white;" data-nome="<?=$row["nome_user1"]?>" id="chat_privado_<?= $row["u1id"]?>" >Privado</button> </td>
                        <td> <button class="btn btn-sucess excluir_amizade" data-dismiss="modal" aria-label="Close" style="margin-left:1vw;border-color:white;color:white;" id="exclude_<?= $row["amizade_id"]?>" >Excluir</button> </td>
                    </tr>
            <?php } ?>

        <?php
    }
}

if(isset($_POST["aceitar_amizade"])){
    $id_pedido=$_POST["id_pedido"];
    $sql="SELECT * FROM pedido_amizade WHERE id=".$id_pedido;
    $results = $conn->query($sql);
    foreach($results as $row){
        $requirinte=$row["id_user_requirinte"];
        $requerido=$row["id_user_requisitado"];
    }
    
    $sql="DELETE FROM pedido_amizade WHERE id=".$id_pedido;
    $results = $conn->query($sql);

    $sql="INSERT INTO amizade (id_user1, id_user2) VALUES ($requirinte, $requerido)";
    $stmt = $conn->query($sql);
    echo("Amizade criada com Sucesso!");
}

if(isset($_POST["recusar_amizade"])){
    $id_pedido=$_POST["id_pedido"];
    
    $sql="DELETE FROM pedido_amizade WHERE id=".$id_pedido;
    $results = $conn->query($sql);

    echo("Amizade recusada com Sucesso!");
}

if(isset($_POST["enviar_pedido_amizade"])){
    $requirinte_id=$_SESSION["id_user"];
    $requisitado_id=$_POST["id_requisitado"];
    $sql = "INSERT INTO pedido_amizade (id_user_requirinte, id_user_requisitado) VALUES ($requirinte_id, $requisitado_id)";
        $stmt = $conn->query($sql);
        echo("Cadastrado com Sucesso!");


}

if(isset($_POST["get_all_requisicoes_de_amizade_desse_user"])){
    $id_user=$_SESSION["id_user"];




    $sql="SELECT pedido_amizade.id_user_requirinte, pedido_amizade.id as id_pedido, usuarios.nome , usuarios.link_foto
    FROM pedido_amizade 
    INNER JOIN usuarios ON usuarios.id = pedido_amizade.id_user_requirinte
    WHERE pedido_amizade.id_user_requisitado = ".$id_user;

    $results = $conn->query($sql);
    ?>
    
        <?php
        foreach($results as $row){
            ?>
                    <tr>
                        <td> 
                            <div class="row">
                                <img style="margin-left:5%;width:4vw;height:8vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                                <h5 class="card-title" style="margin-top:5%;margin-left:5%;background-color:#333; border-color:white"><?= $row["nome"] ?></h5> 
                            </div>
                        </td>
                        
                        <td></td>
                        <td> <button class="btn btn-sucess aceitar_amizade" data-dismiss="modal" aria-label="Close" style="border-color:white;color:white;" id="accept_<?= $row["id_pedido"]?>" >Aceitar</button> </td>
                        <td> <button class="btn btn-sucess recusar_amizade" data-dismiss="modal" aria-label="Close" style="border-color:white;color:white;" id="refuse_<?= $row["id_pedido"]?>" >Recusar</button> </td>
                    

                        
                    </tr>
            <?php
        }
        ?>
    <?php
    $sql="UPDATE pedido_amizade SET last_seen = NOW();";
    $results = $conn->query($sql);
}

if(isset($_GET["get_user_id"])){
    $id_user = $_SESSION["id_user"];
    echo json_encode(array("id_user" => $id_user));
}

if(isset($_GET["get_user_picture"])){
    $sql="SELECT * FROM usuarios WHERE id=".$_SESSION["id_user"];
    $results = $conn->query($sql);
    foreach($results as $row){
        if($row["link_foto"]==NULL){
            return json_encode(array("link_foto" => "https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg"));
        }
    echo json_encode(array("link_foto" => $row["link_foto"]));
    }
}

if(isset($_POST["trocar_pfp"])){
    $sql="UPDATE usuarios SET link_foto='".$_POST["link_foto"]."' WHERE id=".$_POST["user_id"];
    $results = $conn->query($sql);
    echo("foto trocada com sucesso!");
}

if(isset($_POST["get_user_bio"])){
    $sql="SELECT bio FROM usuarios WHERE id=".$_SESSION["id_user"];
    $results = $conn->query($sql);
    foreach($results as $row){
        echo($row["bio"]);
    }
}

if(isset($_POST["set_user_bio"])){
    $sql="UPDATE usuarios SET bio='".$_POST["text"]."' WHERE id=".$_SESSION["id_user"];
    $results = $conn->query($sql);
    echo("bio trocada com sucesso!");
}


//INSERT INTO `amizade` (`id`, `id_user1`, `id_user2`) VALUES (NULL, '2', '3');
?>
