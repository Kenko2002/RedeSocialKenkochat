<?php
include "conexao.php";
session_start(); 
?>

<?php
if(isset($_POST["get_user_id"])){
    $id_user = $_SESSION["id_user"];
    echo json_encode(array("id_user" => $id_user));
}


if(isset($_POST["get_user_picture"])){
    $sql="SELECT * FROM usuarios WHERE id=".$_SESSION["id_user"];
    $results = $conn->query($sql);
    foreach($results as $row){
    echo json_encode(array("link_foto" => $row["link_foto"]));
    }
}

if(isset($_POST["trocar_pfp"])){
    $sql="UPDATE usuarios SET link_foto='".$_POST["link_foto"]."' WHERE id=".$_POST["user_id"];
    $results = $conn->query($sql);
    echo("foto trocada com sucesso!");
}


?>