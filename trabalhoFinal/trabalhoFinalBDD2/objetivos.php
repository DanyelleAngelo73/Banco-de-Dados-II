<?php
require("session.php");
include_once("Connection.php");
$query = new Connection();
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="http://localhost/trabalhoFinalBDD2/style/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="http://localhost/trabalhoFinalBDD2/style/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="http://localhost/trabalhoFinalBDD2/style/style.css" rel="stylesheet">
  <script src="https://globocom.github.io/bootstrap/assets/js/jquery.js"></script>
  <script src="https://globocom.github.io/bootstrap/assets/js/bootstrap-transition.js"></script>
  <script src="https://globocom.github.io/bootstrap/assets/js/bootstrap-dropdown.js"></script>
  <script src="https://globocom.github.io/bootstrap/assets/js/bootstrap-tab.js"></script>
  <script src="https://globocom.github.io/bootstrap/assets/js/bootstrap-collapse.js"></script>
  <script src="https://globocom.github.io/bootstrap/assets/js/bootstrap-modal.js"></script>

  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
  <title>Objetivos</title>
  <script>
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  })
  function excluir(id){

    if(confirm("Tem certeza que deseja exluir esse objetivo?")){
      //pega a variavel, monta o objeto e faz o pedido
      var request = $.ajax({
        url: "excluiObjetivos.php",
        method: "POST",
        data: { codigo : id},
        dataType: "json"
      });
      //se ok com a requisição, trata o resultado (exluido sim ou não)
      request.done(function(resposta) {
        if(resposta==-1){
          alert("O objetivo não pode ser excluído!"); //coloca o resultado dentro do container "#log"
          location.reload();
        }else {
          alert("Objetivo excluído com sucesso!");
          location.reload();
        }
      });
      //caso a requisiçao falhe
      request.fail(function() {
        alert("A requisição falhou!"); //alerta do erro
      });
    }
    else{
      location.reload();
    }
  }
  </script>
</head>
<body>
  <section id="container">
    <?php require("menu.php"); ?>
    <div class="tabbable" id="objetivos"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
        <li class="active" id="myTab"><a href="#tab1" data-toggle="tab">Objetivos</a></li>
        <li><a href="#tab2" data-toggle="tab">Cadastrar Objetivos</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active cadastrado" id="tab1">
          <h2>Objetivos Cadastrados</h2>
          <?php
          $buscaObjetivos= $query->sql_query("SELECT * FROM Objetivos where Id_User = {$_SESSION['Id']}");
          if(mysqli_num_rows($buscaObjetivos)>0){
            echo "<ul id='lista'>";
            while($linha=$buscaObjetivos->fetch_assoc()){
              echo "<li>";
              echo "Nome: ".$linha['Nome']."<br>";
              echo "Descrição: ".$linha['Descricao']."<br>";
              echo "Valor Inicial: ".$linha['Valor_Inicial']."<br>";
              echo "Valor Final: ".$linha['Valor_Final']."<br>";
              echo "Limite para atingir o objetivo: ".$linha['DataFim']."<br>";
              echo "<a class='btn btn-small' href='#' onClick='excluir(".$linha['Id'].");'><i class='icon-remove'></i></a>";
              //código de edição
              echo "<a href='#myModal".$linha['Id']."' role='button' class='btn btn-small' data-toggle='modal'><i class='icon-edit'></i></a>";

              echo "<div id='myModal".$linha['Id']."' class='modal hide fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
                echo "<div class='modal-header'>";
                  echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>";
                  echo "<h3 id='myModalLabel'>Editar ".$linha['Nome']."</h3>";
                echo "</div>";
                echo "<div class='modal-body'>";
                echo "<form action='insereAlteraObjetivos.php?acao=altera&codigo=".$linha['Id']."' method='POST' class='form-horizontal'>";
                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='nome'>Nome: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='nome' required  value=".$linha['Nome'].">";
                echo"  </div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='descricao'>Descrição: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='descricao' required  value=".$linha['Descricao'].">";
                echo"  </div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='valorInicial'>Valor Inicial: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='valorInicial' required  value=".$linha['Valor_Inicial'].">";
                echo"  </div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='valorFinal'>Valor Final: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='valorFinal' required  value=".$linha['Valor_Final'].">";
                echo"  </div>";
                echo"</div>";


                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='dataFim'>Data final: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='dataFim' required  value=".$linha['DataFim'].">";
                echo"  </div>";
                echo"</div>";
                echo "<div class='modal-footer'>";
                  echo "<button type='submit' name='enviar' class='btn' data-dismiss='modal' aria-hidden='true'>Fechar</button>";
                  echo "<button class='btn btn-primary'>Salvar mudanças</button>";
                echo "</div>";
                  echo"</form>";
              echo "</div>";
              echo "</li>";
            }
            echo "</ul>";
          }else{
            echo "<h3>Não existem objetivos cadastrados</h3>";
          }
          ?>
        </div>
        <div class="newObjetivo  tab-pane" id="tab2">
          <h2> Inserir Novo Objetivo</h2><br>
          <div id="formObjetivos">
            <form action="insereAlteraObjetivos.php?acao=insere" method="POST" class="form-horizontal">
              <?php require("formObjetivos.php"); ?>
            </form>
          </div>
        </div>
      </div>
    </section>
  </body>
  </html>
