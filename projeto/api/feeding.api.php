<?php
include "conexao.php";
session_start(); 
?>


<?php

if(isset($_POST["criar_nova_postagem"])){
    $id_user=$_SESSION["id_user"];
    $texto=$_POST["texto"];
    $imagem=$_POST["imagem"];

    $sql = "INSERT INTO postagem (id_user,texto,imagem) VALUES ('$id_user', '$texto','$imagem')";
    $stmt = $conn->query($sql);
}
?>

<?php
if(isset($_POST["feeding_geral"])){
    $id_user=$_SESSION["id_user"];
    $query = 'SELECT postagem.id as postid,esc.nome,esc.link_foto,postagem.texto,postagem.imagem
    FROM postagem
    INNER JOIN usuarios esc ON esc.id = postagem.id_user
    ORDER BY postagem.id DESC';

    $results = $conn->query($query);

    $query2 = 'UPDATE postagem SET last_seen = NOW() WHERE id_user ='.$id_user;

    $results2 = $conn->query($query2);

}

if(isset($_POST["feeding_amigos"])){
    $id_user=$_SESSION["id_user"];
    $query = '
  SELECT 
    postagem.id as postid,
    esc.nome,
    esc.link_foto,
    postagem.texto,
    postagem.imagem,
    amizade.id_user1,
    amizade.id_user2
  FROM 
    postagem
  INNER JOIN usuarios esc ON esc.id = postagem.id_user
  INNER JOIN amizade ON (esc.id = amizade.id_user1 OR esc.id = amizade.id_user2)
  WHERE 
    (id_user1 = ? OR id_user2 = ?)
  ORDER BY 
    postagem.id DESC';

    // Preparar a declaração SQL
    $stmt = $conn->prepare($query);

    // Vincular os parâmetros e executar a consulta
    $stmt->bind_param("ii", $_SESSION["id_user"], $_SESSION["id_user"]);
    $stmt->execute();

    $results = $stmt->get_result();

    $query2 = 'UPDATE postagem
    SET last_seen = NOW()
    WHERE id_user IN (
        SELECT id_user1 FROM amizade WHERE id_user2 = '.$id_user.'
        UNION 
        SELECT id_user2 FROM amizade WHERE id_user1 = '.$id_user.'
    )';
    $stmt2 = $conn->prepare($query2);
    $stmt2->execute();


}

if(isset($_POST["feeding_minhas_postagens"])){
    $id_user=$_SESSION["id_user"];
    $query = "  SELECT 
                    postagem.id as postid,
                    esc.nome,
                    esc.link_foto,
                    postagem.texto,
                    postagem.imagem,
                    postagem.id_user
                FROM 
                    postagem
                INNER JOIN usuarios esc ON esc.id = postagem.id_user
                WHERE 
                    postagem.id_user = $id_user
                ORDER BY 
                    postagem.id DESC;";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $results = $stmt->get_result();

    $sql = "UPDATE postagem SET last_seen = NOW() WHERE postagem.id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_user); // Assuming $id_user is an integer
    $stmt->execute();
}

?>



<?php
if(isset($_POST["feeding_amigos"]) || isset($_POST["feeding_geral"]) || isset($_POST["feeding_minhas_postagens"])){
?>
    <style>
            /* Estilo personalizado para a div */
            .custom-div2 {
                width: 100%;
                overflow-y: scroll; /* Adicionar barra de rolagem vertical quando necessário */
                border: 0px solid #ccc;
                padding: 10px;
            }
            .custom-div2::-webkit-scrollbar {
                width: 10px; /* Largura da barra de rolagem */
            }
            .custom-div2::-webkit-scrollbar-thumb {
                background-color: #444; /* Cor da barra de rolagem */
            }
        </style>
        <style>
        /* Estilize a imagem */
        .fullscreen-image {
            display: none; /* Inicialmente oculto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: rgba(0, 0, 0, 0.8); /* Cor de fundo para sobreposição */
        }
        .fullscreen-image img {
            display: block;
            margin: auto;
            max-width: 90%;
            max-height: 90%;
        }
        </style>
        
        <div class="container" style="background-color:#333">
        <div class="row" style="background-color:#333;" >
            <div style="width: 99%;">
                <?php foreach ($results as $row) { ?>

                    

                    <div class="card mb-3" style="background-color:#444; border-radius:0px;">
                        <div class="card-body"  style="background-color:#444; padding: 2%">
                            <div class="row">
                                <img style="margin-left:2%;width:3vw;height:6vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                                <h5 class="card-title" style="margin-left:2%;margin-top:2%;background-color:#444"><?= $row["nome"] ?></h5>
                            </div>
                            <p class="card-text custom-div2" style="max-height:20vh"> <?= $row["texto"] ?></p>
                            
                            <?php
                                if($row["imagem"]!=""){
                                    ?>
                                    <!-- Imagem Miniatura -->
                                    <img id="thumbnail-<?=$row["postid"];?>" style="max-height:20vh;max-width:50vw" src="<?= $row["imagem"] ?>" >

                                    <!-- Imagem em Tela Cheia -->
                                    <div class="fullscreen-image"id="fullscreen-image-<?=$row["postid"];?>">
                                    <span id="close-btn-<?=$row["postid"];?>" style="position: absolute; top: 10px; right: 10px; cursor: pointer; color: white; font-size: 24px;">&times;</span>
                                    <img class="fullscreen-img" id="fullscreen-img-<?=$row["postid"];?>">
                                    </div>
                                    <?php
                                }
                            ?>

                        </div>

                        <?php
                                //pegando o numero de comentarios relacionados a uma postagem
                                $query = 'SELECT comentarios.id,comentarios.id_user,comentarios.id_postagem,comentarios.texto,comentarios.imagem,esc.nome,esc.link_foto
                                FROM comentarios
                                INNER JOIN usuarios esc ON esc.id = comentarios.id_user
                                WHERE id_postagem='.$row["postid"].'
                                ORDER BY comentarios.id DESC';

                                $ans= mysqli_query($conn, $query);
                        ?>

                        <div class="row" style="margin-left:0.5vw;display:inline-block;">
                            <button class="btn btn-sucess comentarios" style="width:48%;padding:2px;max-height:10%;max-width:100%;" id="comentarios_<?=$row["postid"]?> "> <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-chat-square-text" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
                            </svg> <span style="color:white"><?= mysqli_num_rows($ans); ?></span> </button>
                            
                            



                            <button class="btn btn-sucess compartilhar" style="width:48%;padding:2px;max-height:10%;max-width:100%;" id="compartilhar_<?=$row["postid"]?> "> <svg id="svg_compartilhar_<?=$row["postid"]?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-repeat" viewBox="0 0 16 16">
                            <path d="M11 5.466V4H5a4 4 0 0 0-3.584 5.777.5.5 0 1 1-.896.446A5 5 0 0 1 5 3h6V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192m3.81.086a.5.5 0 0 1 .67.225A5 5 0 0 1 11 13H5v1.466a.25.25 0 0 1-.41.192l-2.36-1.966a.25.25 0 0 1 0-.384l2.36-1.966a.25.25 0 0 1 .41.192V12h6a4 4 0 0 0 3.585-5.777.5.5 0 0 1 .225-.67Z"/>
                            </svg> </button>
                        </div>

                    </div>

                    <script>
                        try{
                            // Função para exibir a imagem em tela cheia
                            document.getElementById('thumbnail-<?=$row["postid"];?>').addEventListener('click', function() {
                                var imageUrl = this.src;
                                document.getElementById('fullscreen-img-<?=$row["postid"];?>').src = imageUrl;
                                document.getElementById('fullscreen-image-<?=$row["postid"];?>').style.display = 'block';
                            });

                            // Função para fechar a imagem em tela cheia
                            document.getElementById('close-btn-<?=$row["postid"];?>').addEventListener('click', function() {
                                document.getElementById('fullscreen-image-<?=$row["postid"];?>').style.display = 'none';
                            });
                        
                                // Adicionar um event listener para pressionar a tecla "Esc"
                                document.addEventListener('keydown', function(event) {
                                    if (event.keyCode === 27) { // Verifica se a tecla pressionada é "Esc"
                                        document.getElementById('fullscreen-image-<?=$row["postid"];?>').style.display = 'none';
                                    }
                                });
                        } catch (error) {
                        
                        }
                        
                    </script>

                <?php } ?>

    <?php
}
?>

<?php if(isset($_POST["query_comentarios"])){
    //pegando a postagem pai
    $query = 'SELECT postagem.id as postid,esc.nome,esc.link_foto,postagem.texto,postagem.imagem
    FROM postagem
    INNER JOIN usuarios esc ON esc.id = postagem.id_user
    WHERE postagem.id='.$_POST["id_postagem"].'
    ORDER BY postagem.id DESC';
    $results = $conn->query($query);
    ?>
    <style>
            /* Estilo personalizado para a div */
            .custom-div2 {
                width: 100%;
                overflow-y: scroll; /* Adicionar barra de rolagem vertical quando necessário */
                border: 0px solid #ccc;
                padding: 10px;
            }
            .custom-div2::-webkit-scrollbar {
                width: 10px; /* Largura da barra de rolagem */
            }
            .custom-div2::-webkit-scrollbar-thumb {
                background-color: #444; /* Cor da barra de rolagem */
            }
        </style>
        <style>
        /* Estilize a imagem */
        .fullscreen-image {
            display: none; /* Inicialmente oculto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: rgba(0, 0, 0, 0.8); /* Cor de fundo para sobreposição */
        }
        .fullscreen-image img {
            display: block;
            margin: auto;
            max-width: 90%;
            max-height: 90%;
        }
        </style>
        
        <div class="container" style="background-color:#333">
        <div class="row" style="background-color:#333;" >
            <div style="width: 99%;">
                <?php foreach ($results as $row) { ?>

                    

                    <div class="card mb-3" style="background-color:#444; border-radius:0px;border-color:white;">
                        <div class="card-body"  style="background-color:#444; padding: 2%">
                            <div class="row">
                                <img style="margin-left:2%;width:3vw;height:6vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                                <h5 class="card-title" style="margin-left:2%;margin-top:2%;background-color:#444"><?= $row["nome"] ?> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cup-hot-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M.5 6a.5.5 0 0 0-.488.608l1.652 7.434A2.5 2.5 0 0 0 4.104 16h5.792a2.5 2.5 0 0 0 2.44-1.958l.131-.59a3 3 0 0 0 1.3-5.854l.221-.99A.5.5 0 0 0 13.5 6zM13 12.5a2 2 0 0 1-.316-.025l.867-3.898A2.001 2.001 0 0 1 13 12.5"/>
                                <path d="m4.4.8-.003.004-.014.019a4 4 0 0 0-.204.31 2 2 0 0 0-.141.267c-.026.06-.034.092-.037.103v.004a.6.6 0 0 0 .091.248c.075.133.178.272.308.445l.01.012c.118.158.26.347.37.543.112.2.22.455.22.745 0 .188-.065.368-.119.494a3 3 0 0 1-.202.388 5 5 0 0 1-.253.382l-.018.025-.005.008-.002.002A.5.5 0 0 1 3.6 4.2l.003-.004.014-.019a4 4 0 0 0 .204-.31 2 2 0 0 0 .141-.267c.026-.06.034-.092.037-.103a.6.6 0 0 0-.09-.252A4 4 0 0 0 3.6 2.8l-.01-.012a5 5 0 0 1-.37-.543A1.53 1.53 0 0 1 3 1.5c0-.188.065-.368.119-.494.059-.138.134-.274.202-.388a6 6 0 0 1 .253-.382l.025-.035A.5.5 0 0 1 4.4.8m3 0-.003.004-.014.019a4 4 0 0 0-.204.31 2 2 0 0 0-.141.267c-.026.06-.034.092-.037.103v.004a.6.6 0 0 0 .091.248c.075.133.178.272.308.445l.01.012c.118.158.26.347.37.543.112.2.22.455.22.745 0 .188-.065.368-.119.494a3 3 0 0 1-.202.388 5 5 0 0 1-.253.382l-.018.025-.005.008-.002.002A.5.5 0 0 1 6.6 4.2l.003-.004.014-.019a4 4 0 0 0 .204-.31 2 2 0 0 0 .141-.267c.026-.06.034-.092.037-.103a.6.6 0 0 0-.09-.252A4 4 0 0 0 6.6 2.8l-.01-.012a5 5 0 0 1-.37-.543A1.53 1.53 0 0 1 6 1.5c0-.188.065-.368.119-.494.059-.138.134-.274.202-.388a6 6 0 0 1 .253-.382l.025-.035A.5.5 0 0 1 7.4.8m3 0-.003.004-.014.019a4 4 0 0 0-.204.31 2 2 0 0 0-.141.267c-.026.06-.034.092-.037.103v.004a.6.6 0 0 0 .091.248c.075.133.178.272.308.445l.01.012c.118.158.26.347.37.543.112.2.22.455.22.745 0 .188-.065.368-.119.494a3 3 0 0 1-.202.388 5 5 0 0 1-.252.382l-.019.025-.005.008-.002.002A.5.5 0 0 1 9.6 4.2l.003-.004.014-.019a4 4 0 0 0 .204-.31 2 2 0 0 0 .141-.267c.026-.06.034-.092.037-.103a.6.6 0 0 0-.09-.252A4 4 0 0 0 9.6 2.8l-.01-.012a5 5 0 0 1-.37-.543A1.53 1.53 0 0 1 9 1.5c0-.188.065-.368.119-.494.059-.138.134-.274.202-.388a6 6 0 0 1 .253-.382l.025-.035A.5.5 0 0 1 10.4.8"/>
                            </svg></h5>
                            </div>
                            <p class="card-text custom-div2" style="max-height:20vh"> <?= $row["texto"] ?></p>
                            
                            <?php
                                if($row["imagem"]!=""){
                                    ?>
                                    <!-- Imagem Miniatura -->
                                    <img id="thumbnail-<?=$row["postid"];?>" style="max-height:20vh;max-width:50vw" src="<?= $row["imagem"] ?>" >

                                    <!-- Imagem em Tela Cheia -->
                                    <div class="fullscreen-image"id="fullscreen-image-<?=$row["postid"];?>">
                                    <span id="close-btn-<?=$row["postid"];?>" style="position: absolute; top: 10px; right: 10px; cursor: pointer; color: white; font-size: 24px;">&times;</span>
                                    <img class="fullscreen-img" id="fullscreen-img-<?=$row["postid"];?>">
                                    </div>
                                    <?php
                                }
                            ?>

                        </div>

                    </div>

                    <script>
                        try{
                            // Função para exibir a imagem em tela cheia
                            document.getElementById('thumbnail-<?=$row["postid"];?>').addEventListener('click', function() {
                                var imageUrl = this.src;
                                document.getElementById('fullscreen-img-<?=$row["postid"];?>').src = imageUrl;
                                document.getElementById('fullscreen-image-<?=$row["postid"];?>').style.display = 'block';
                            });

                            // Função para fechar a imagem em tela cheia
                            document.getElementById('close-btn-<?=$row["postid"];?>').addEventListener('click', function() {
                                document.getElementById('fullscreen-image-<?=$row["postid"];?>').style.display = 'none';
                            });
                        
                                // Adicionar um event listener para pressionar a tecla "Esc"
                                document.addEventListener('keydown', function(event) {
                                    if (event.keyCode === 27) { // Verifica se a tecla pressionada é "Esc"
                                        document.getElementById('fullscreen-image-<?=$row["postid"];?>').style.display = 'none';
                                    }
                                });
                        } catch (error) {
                        
                        }
                        
                    </script>

                <?php } 

    //pegando seus comentários
    $query = 'SELECT comentarios.id,comentarios.id_user,comentarios.id_postagem,comentarios.texto,comentarios.imagem,esc.nome,esc.link_foto
    FROM comentarios
    INNER JOIN usuarios esc ON esc.id = comentarios.id_user
    WHERE id_postagem='.$_POST["id_postagem"].'
    ORDER BY comentarios.id DESC';

    $results = $conn->query($query);

     foreach ($results as $row) { ?>

                    

        <div class="card mb-3" style="background-color:#444; border-radius:0px;">
            <div class="card-body"  style="background-color:#444; padding: 2%">
                <div class="row">
                    <img style="margin-left:2%;width:3vw;height:6vh;border-radius:50%" src="<?= $row["link_foto"] ?>" >
                    <h5 class="card-title" style="margin-left:2%;margin-top:2%;background-color:#444"><?= $row["nome"] ?></h5>
                </div>
                <p class="card-text custom-div2" style="max-height:20vh"> <?= $row["texto"] ?></p>
                
                <?php
                    if($row["imagem"]!=""){
                        ?>
                        <!-- Imagem Miniatura -->
                        <img id="thumbnail-comment-<?=$row["id"];?>" style="max-height:20vh;max-width:50vw" src="<?= $row["imagem"] ?>" >
                        
                        <!-- Imagem em Tela Cheia -->
                        <div class="fullscreen-image"id="fullscreen-image-comment-<?=$row["id"];?>">
                        <span id="close-btn-comment-<?=$row["id"];?>" style="position: absolute; top: 10px; right: 10px; cursor: pointer; color: white; font-size: 24px;">&times;</span>
                        <img class="fullscreen-img" id="fullscreen-img-comment-<?=$row["id"];?>">
                        </div>
                        <?php
                    }
                ?>

            </div>


        </div>

        <script>
            try{
                // Função para exibir a imagem em tela cheia
                document.getElementById('thumbnail-comment-<?=$row["id"];?>').addEventListener('click', function() {
                    var imageUrl = this.src;
                    document.getElementById('fullscreen-img-comment-<?=$row["id"];?>').src = imageUrl;
                    document.getElementById('fullscreen-image-comment-<?=$row["id"];?>').style.display = 'block';
                });

                // Função para fechar a imagem em tela cheia
                document.getElementById('close-btn-comment-<?=$row["id"];?>').addEventListener('click', function() {
                    document.getElementById('fullscreen-image-comment-<?=$row["id"];?>').style.display = 'none';
                });
            
                    // Adicionar um event listener para pressionar a tecla "Esc"
                    document.addEventListener('keydown', function(event) {
                        if (event.keyCode === 27) { // Verifica se a tecla pressionada é "Esc"
                            document.getElementById('fullscreen-image-comment-<?=$row["id"];?>').style.display = 'none';
                        }
                    });
            } catch {
            
            }
            
        </script>

    <?php } ?>

<?php }?>


<?php if(isset($_POST["compartilhar"])){
    $id_postagem_alvo=$_POST["id_postagem"];
    $id_user=$_SESSION["id_user"];

    $sql = "SELECT texto,imagem FROM postagem WHERE id=".$id_postagem_alvo;
    $ans = $conn->query($sql);
    foreach($ans as $an){
        $texto=$an["texto"];
        $imagem=$an["imagem"];
    }
    $sql = "INSERT INTO postagem (id_user,texto,imagem) VALUES ('$id_user', '$texto','$imagem')";
    $stmt = $conn->query($sql);
}
?>

<?php if(isset($_POST["novo_comentario"])){
    $id_postagem_alvo=$_POST["id_postagem"];
    $id_user=$_SESSION["id_user"];
    $texto=$_POST["texto"];
    $imagem=$_POST["imagem"];

    $sql = "INSERT INTO comentarios (id_user,id_postagem,texto,imagem) VALUES ('$id_user','$id_postagem_alvo', '$texto','$imagem')";
    $stmt = $conn->query($sql);
}



?>