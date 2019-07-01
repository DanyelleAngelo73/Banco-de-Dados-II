<?php
require("session.php");
require_once("Connection.php");
$query=new Connection();
$acao = $_GET['acao'];
if(isset($_POST["nome"])&&($_POST["cor"])){
  $nome = $_POST["nome"];
  $cor= $_POST["cor"];
  if($acao=='insere'){
    $insertCategoria= $query->sql_query("INSERT INTO Categoria values (null,{$_SESSION['Id']},'{$nome}','{$cor}')");
  }
  else{
    $Id = $_GET['codigo'];
    $updateTransacoes= $query->sql_query("UPDATE Categoria set Nome = '{$nome}',Cor='{$cor}' WHERE Id_User={$_SESSION['Id']} and Id={$Id}");
  }
  header("location:categorias.php");
}


?>
