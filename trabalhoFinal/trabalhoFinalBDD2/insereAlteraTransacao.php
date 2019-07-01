<?php
  require("session.php");
  require_once("Connection.php");
  $query=new Connection();
  $tipo = $_GET['tipo'];
  $acao=$_GET['acao'];
  if(isset($_POST["descricao"])&&($_POST["situacao"])&&($_POST["categoria"])&&($_POST["dataTra"])&&($_POST["periodicidade"])&&($_POST["valor"])){
    $descricao = $_POST["descricao"];
    $situacao= $_POST["situacao"];
    $categoria= $_POST["categoria"];
    $dataTra = $_POST["dataTra"];
    $periodicidade= $_POST["periodicidade"];
    $valor= $_POST["valor"];
    if($acao=='insere'){
      $insertTransacoes= $query->sql_query("INSERT INTO Transacoes values (null,{$_SESSION['Id']},{$valor},'{$situacao}','{$periodicidade}','{$descricao}','$dataTra',{$categoria})");

      if($tipo == 'R'){//a transação é uma receita
        $insertReceita= $query->sql_query("INSERT INTO Receitas values({$query->ultimoId},{$_SESSION['Id']})");
        header("location:receitas.php");
      }else if($tipo == 'DD'){//a transação é despesa no dinheiro
        $juros = $_POST['juros'];
        $insertDespesasD= $query->sql_query("INSERT INTO DespesasNoDinheiro values({$query->ultimoId},{$_SESSION['Id']},{$juros})");
        header("location:despesasDinheiro.php");
      }else if($tipo == 'DC'){//a transação é do tipo despesa no cartão;
        if(isset($_POST["cartao"])){
          $cartao = $_POST["cartao"];
          $juros = $_POST['juros'];
          $insertDespesasD= $query->sql_query("INSERT INTO DespesasNoCartao values({$query->ultimoId},{$_SESSION['Id']},{$juros},{$cartao})");

        }
    }
  }else{
    $Id = $_GET['codigo'];
    $updateTransacoes= $query->sql_query("UPDATE Transacoes SET Valor={$valor}, Situacao='{$situacao}',Peridiocidade='{$periodicidade}',Descricao='{$descricao}',DataTra='$dataTra',IdCategoria={$categoria} WHERE Id_User={$_SESSION['Id']} and Id={$Id}");


    if($tipo=='DC'){
      $cartao = $_POST["cartao"];
      $juros = $_POST['juros'];
      $insertDespesasD= $query->sql_query("UPDATE DespesasNoCartao SET Juros = {$juros},NumeroCartao={$cartao} WHERE Id_User={$_SESSION['Id']} and Id={$Id}");
      header("location:despesasCartao.php");
    }
    if($tipo=='DD'){
      $juros = $_POST['juros'];
      $insertDespesasD= $query->sql_query("UPDATE DespesasNoCartao SET Juros = {$juros} WHERE Id_User={$_SESSION['Id']} and Id={$Id}");

     header("location:despesasDinheiro.php");
    }
    else {
      header("location:receitas.php");
    }
  }
}

?>
