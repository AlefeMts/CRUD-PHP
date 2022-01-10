<?php
    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $dbname = "teste";
    $porta = 3307;

//Criando a conexão
$conn = new PDO("mysql:host=$host;port=$porta;dbname=".$dbname, $usuario, $senha);

if(!$conn){
    die("Falha na conexão: " . mysqli_connect_error());
}else{
    //echo "Conexão realizada com sucesso";
}
?>