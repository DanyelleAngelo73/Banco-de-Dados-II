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
  <title>Receitas</title>
  <script>
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  })
  function excluir(id){
    if(confirm("Tem certeza que deseja exluir essa receita?")){
      //pega a variavel, monta o objeto e faz o pedido
      var request = $.ajax({
        url: "excluiReceitas.php",
        method: "POST",
        data: { codigo : id},
        dataType: "json"
      });
      //se ok com a requisição, trata o resultado (exluido sim ou não)
      request.done(function(resposta) {
        if(resposta==-1){
          alert("A receita não pode ser excluída!"); //coloca o resultado dentro do container "#log"
          location.reload();
        }else {
          alert("Receita excluída com sucesso!");
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
    <div class="tabbable" id="transacoes"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
        <li class="active" id="myTab"><a href="#tab1" data-toggle="tab">Receitas</a></li>
        <li><a href="#tab2" data-toggle="tab">Cadastrar Receitas</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active cadastrado" id="tab1">
          <h2>Receitas Cadastradas</h2>
          <?php
          $buscaReceita = $query->sql_query("SELECT * FROM visualizaReceitas where Id_User = {$_SESSION['Id']}");
          if(mysqli_num_rows($buscaReceita)>0){//existem receitas cadastradas
            echo "<ul id='lista'>";
            while($linha=$buscaReceita->fetch_assoc()){//vamos listar as receitas
              echo "<li style='border-bottom:17px solid ".$linha['Cor']." '>";
              echo "Descrição: ".$linha['Descricao']."<br>";
              echo "Situação: ".$linha['Situacao']."<br>";
              echo "Categoria: ".$linha['NomeCategoria']."<br>";//
              echo "Data: ".$linha['DataTra']."<br>";
              echo "Periodicidade: ".$linha['Periodicidade']."<br>";
              echo "Valor: ".$linha['Valor']."<br>";
              echo "<a class='btn btn-small' href='#' onClick='excluir(".$linha['IdTransacao'].");'><i class='icon-remove'></i></a>";
              //código de edição
              echo "<a href='#myModal".$linha['IdTransacao']."' role='button' class='btn btn-small' data-toggle='modal'><i class='icon-edit'></i></a>";

              echo "<div id='myModal".$linha['IdTransacao']."' class='modal hide fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
                echo "<div class='modal-header'>";
                  echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>";
                  echo "<h3 id='myModalLabel'>Editar ".$linha['Descricao']."</h3>";
                echo "</div>";
                echo "<div class='modal-body'>";
                $buscaCategorias = $query->sql_query("SELECT * FROM Categoria where Id_User = {$_SESSION['Id']}");

                echo "<form action='insereAlteraTransacao.php?acao=altera&codigo=".$linha['IdTransacao']."&tipo=R' method='POST' class='form-horizontal'>";


                echo  "<div class='control-group'>";
                echo  "<label class='control-label' for='descricao'>Descrição: </label>";
                echo  "<div class='controls'>";
                echo"<input type='text' name='descricao' required placeholder='Descrição' value=".$linha['Descricao'].">";
                echo"</div>";
                echo"</div>";


                echo"<div class='control-group'>";
                echo"<label class='control-label' for='situacao'>Situação</label>";
                echo"<div class='controls'>";
                echo"<select name='situacao' required placeholder='Situação' value=".$linha['Situacao'].">";
                echo  "<option value='value=".$linha['Situacao']."'>".$linha['Situacao']."</option>";
                echo"<option value='A pagar'>A pagar</option>";
                echo"<option value='Atrasada'>Atrasada</option>";
                echo"</select>";
                echo"</div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"<label class='control-label' for='categoria'>Categoria</label>";
                echo"<div class='controls'>";
                echo"<select name='categoria' required placeholder='Categoria' value=".$linha['IdCategoria'].">";
                        if(mysqli_num_rows($buscaCategorias)>0){
                          while($categoria=$buscaCategorias->fetch_assoc()){
                            echo  "<option value=".$categoria['Id'].">".$categoria['Nome']."</option>";
                          }
                        }

                  echo" </select>";
                echo"</div>";
                echo"</div>";



                echo"<div class='control-group'>";
                echo"<label class='control-label' for='data'>Data de Transaçãp: </label>";
                echo"<div class='controls'>";
                echo"<input type='text' name='dataTra' required placeholder='YYYY-MM-DD' value=".$linha['DataTra'].">";
                echo"</div>";
                echo"</div>";


                echo"<div class='control-group'>";
                echo"<label class='control-label' for='periodicidade'>Periodicidade</label>";
                echo"<div class='controls'>";
                echo"<select name='periodicidade' required placeholder='Periodicidade' value=".$linha['Periodicidade'].">";
                echo"<option value='Semanal'>Nenhuma</option>";
                echo"<option value='Semanal'>Semanal</option>";
                echo"<option value='Quinzenal'>Quinzenal</option>";
                echo"<option value='Mensal'>Mensal</option>";
                  echo"<option value='Anual'>Anual</option>";
                echo"</select>";
                echo"</div>";
                echo"</div>";



                echo"<div class='control-group'>";
                echo"<label class='control-label' for='valor'>Valor: R$ </label>";
                echo"<div class='controls'>";
                echo"<input type='text' name='valor' required placeholder='Valor' value=".$linha['Valor'].">";
                echo"</div>";
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
            echo "<h3>Não existem receitas cadastradas</h3>";
          }
          ?>
        </div>
        <div class="newTransacao tab-pane" id="tab2">
          <h2> Inserir Nova Receita</h2><br>
          <div id="formTransacao">
            <form action="insereAlteraTransacao.php?acao=insere&tipo=R" method="POST" class="form-horizontal">
              <?php require("formReceitas.php"); ?>
            </form>
          </div>
        </div>
      </div>

    </section>
  </body>
  </html>
