<?php
include "conexao.php";
?>

<?php
if(isset($_POST["cadastrar"])){
    $user_valido=true;
    $user = $_POST["user"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios";
    $ans = $conn->query($sql);

    foreach($ans as $an){
        if(($user) == ($an["nome"]) ){
            echo("Usuario ja existe!");
            $user_valido=false;
        }
    }

    if($user_valido){
        $sql = "INSERT INTO usuarios (nome, senha,link_foto) VALUES ('$user', '$senha','https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg')";
        $stmt = $conn->query($sql);
        echo("Cadastrado com Sucesso!");

        $last_id = $conn->insert_id;
        $sql = "INSERT INTO sala_usuarios (id_user, id_sala , eh_admin)
        VALUES (".$last_id.",1,'n');";
        $stmt = $conn->query($sql);

        $sql = "INSERT INTO usuario_tabuleiro (id_user, id_sala , eh_admin)
        VALUES (".$last_id.",1,'n');";
        $stmt = $conn->query($sql);

    }

}

if(isset($_POST["login"])){
    $login_valido=false;
    $user = $_POST["user"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios";
    $ans = $conn->query($sql);

    foreach($ans as $an){
        if(($user == $an["nome"]) && ($senha == $an["senha"])) {
            echo("Login Efetuado!");
            $login_valido=true;
            session_start();
            $_SESSION["user"]=$user;
            $_SESSION["id_user"]=$an["id"];
        }      
    }
    if($login_valido==false){
        echo("Login Inválido!");
    }
}




?>
