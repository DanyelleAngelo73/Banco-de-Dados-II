<?php
  require("session.php");
  require_once("Connection.php");
  $query = new Connection();
  $acao = $_GET['acao'];
  if(isset($_POST['nome'])&&($_POST['bandeira'])&&($_POST['limite'])&&($_POST['fechamento'])&&($_POST['vencimento'])){
    $nome = $_POST['nome'];
    $bandeira =$_POST['bandeira'];
    $limite = $_POST['limite'];
    $fechamento = $_POST['fechamento'];
    $vencimento = $_POST['vencimento'];
    if($acao=='insere'){
      $insertTransacoes= $query->sql_query("INSERT INTO Cartao values (null,{$_SESSION['Id']},'{$nome}','{$bandeira}',$limite,'{$fechamento}','$vencimento')");
    }else{
      $Id = $_GET['codigo'];
      $updateTransacoes= $query->sql_query("UPDATE Cartao set nome = '{$nome}',bandeira='{$bandeira}',limite ={$limite}, DataFechamento='{$fechamento}',DataPagamento=$vencimento WHERE Id_User={$_SESSION['Id']} and Id={$Id}");
    }
    header("location:cartao.php");
  }


?>
