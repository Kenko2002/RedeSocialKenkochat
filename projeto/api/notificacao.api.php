<?php
include "conexao.php";
session_start(); 


if(isset($_POST["get_num_notificacoes"])){
  $id_user=$_SESSION["id_user"];
  $sql1="SELECT usuarios.link_foto FROM pedido_amizade 
  INNER JOIN usuarios ON pedido_amizade.id_user_requirinte = usuarios.id
  WHERE id_user_requisitado=$id_user AND pedido_amizade.last_seen=pedido_amizade.data_envio LIMIT 1;";

  $sql2="SELECT sala_usuarios.id_sala, COUNT(*) AS total_registros , sala_usuarios.last_seen, mensagem.data_envio,sala.nome FROM sala_usuarios
  JOIN mensagem on sala_usuarios.id_sala = mensagem.id_sala
  JOIN sala on sala.id=sala_usuarios.id_sala
  WHERE id_user=$id_user AND sala_usuarios.last_seen < mensagem.data_envio
  group by sala_usuarios.id_sala";

  $sql3="SELECT postagem.texto,postagem.last_seen,comentarios.data_envio, usuarios.nome AS nome_comentarista, usuarios.link_foto
        FROM postagem 
        INNER JOIN comentarios ON comentarios.id_postagem=postagem.id
        INNER JOIN usuarios ON comentarios.id_user=usuarios.id
        WHERE postagem.last_seen<comentarios.data_envio AND postagem.id_user=$id_user";
        $ans=$conn->query($sql3);

  $ans1 = $conn->query($sql1);
  $ans2 = $conn->query($sql2);
  $ans3 = $conn->query($sql3);
  echo($ans1->num_rows+$ans2->num_rows+$ans3->num_rows);
  exit();
}?>

<style>
/* Estilo base do botão */
.button {
  display: inline-block;
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 5px;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  background-color: #4CAF50;
  color: white;
  border: none;
}

/* Efeito de hover */
.button:hover {
  background-color: #45a049;
}

/* Efeito de foco */
.button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.2);
}

/* Efeito de clique */
.button:active {
  transform: translateY(1px);
}
</style>


<?php


if(isset($_POST["get_all_notificacoes"])){
    $id_user=$_SESSION['id_user'];

      //GERANDO NOTIFICAÇÕES DE PEDIDOS DE AMIZADE
      $sql="
      SELECT usuarios.link_foto FROM pedido_amizade 
      INNER JOIN usuarios ON pedido_amizade.id_user_requirinte = usuarios.id
      WHERE id_user_requisitado=$id_user AND pedido_amizade.last_seen=pedido_amizade.data_envio GROUP BY usuarios.link_foto LIMIT 3";
      $ans = $conn->query($sql);

      if($ans->num_rows!=0){
      ?>

      <div nome_sala="NULL" href="./../perfil/perfil.php" class="menu-item"> 
        <button class="button" style="background-color:#333; width:100%;font-size: 11px;"> Pedido de Amizade <br>

        <?php
        foreach($ans as $an){
        ?>
          <img style="width:2vw;height:4vh;border-radius:50%;margin-top:2px;" src="<?= $an["link_foto"] ?>" >
        <?php 
        }
        ?>

        </button> 
      </div>

        <?php
      }


      //GERANDO NOTIFICAÇÕES DE SALAS COM NOVAS MENSAGENS
      $sql="
      SELECT sala_usuarios.id_sala, COUNT(*) AS total_registros , sala_usuarios.last_seen, mensagem.data_envio,sala.nome FROM sala_usuarios
      JOIN mensagem on sala_usuarios.id_sala = mensagem.id_sala
      JOIN sala on sala.id=sala_usuarios.id_sala
      WHERE id_user=$id_user AND sala_usuarios.last_seen < mensagem.data_envio
      group by sala_usuarios.id_sala";
      $ans = $conn->query($sql);
      foreach($ans as $an){
        ?>
  
      <div nome_sala="<?= $an["nome"];?>" href="./../chat/chat.php" class="menu-item"> <button class="button" style="width:100%;font-size: 12px;background-color:#333"> <?= $an["nome"];?> (<?= $an["total_registros"];?>)</button> </div>
        
        <?php
      }


      //GERANDO NOTIFICAÇÕES DE COMENTÁRIOS EM FEEDING
      $sql="SELECT postagem.id as idpost,postagem.texto,postagem.last_seen,comentarios.data_envio, usuarios.nome AS nome_comentarista, usuarios.link_foto
      FROM postagem 
      INNER JOIN comentarios ON comentarios.id_postagem=postagem.id
      INNER JOIN usuarios ON comentarios.id_user=usuarios.id
      WHERE postagem.last_seen<comentarios.data_envio AND postagem.id_user=$id_user";
      $ans=$conn->query($sql);
      foreach($ans as $an){
        ?>
  
      <div id_postagem="<?= $an["idpost"]; ?>" nome_sala="NULL" href="./../feeding/feeding.php" class="menu-item feeding_notify"> <button class="button" style="width:100%;font-size: 12px;background-color:#333"> <?=$an["nome_comentarista"]?> comentou na sua postagem.</button> </div>
        
        <?php
      }


      


  
}




?>