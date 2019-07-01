<?php
  require("session.php");
  require_once("Connection.php");
  $query=new Connection();
  if(isset($_POST["nome"])&&($_POST["descricao"])&&($_POST["valorInicial"])&&($_POST["valorFinal"])&&($_POST["dataFim"])){
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $valorIncial = $_POST["valorInicial"];
    $valorFinal = $_POST["valorFinal"];
    $dataFim = $_POST["dataFim"];
    $acao=$_GET['acao'];
    if($acao=='insere'){
      $insertCategoria= $query->sql_query("INSERT INTO Objetivos values (null,{$_SESSION['Id']},'{$nome}','{$descricao}',{$valorIncial},{$valorFinal},'{$dataFim}','{$cor}')");
    }else{
      $Id = $_GET['codigo'];
      $updateTransacoes= $query->sql_query("UPDATE Objetivos set Nome = '{$nome}',Descricao= '{$descricao}',Valor_Inicial={$valorIncial},valor_Final={$valorFinal},DataFim='{$dataFim}',Cor='{$cor}' WHERE Id_User={$_SESSION['Id']} and Id={$Id}");
    }
    header("location:objetivos.php");
  }
?>
