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
  <title>Despesas no Dinheiro</title>
  <script>
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  })
  function excluir(id){
    alert(id);
    if(confirm("Tem certeza que deseja exluir essa despesa?")){
      //pega a variavel, monta o objeto e faz o pedido
      var request = $.ajax({
        url: "excluiDespesasD.php",
        method: "POST",
        data: { codigo : id},
        dataType: "json"
      });
      //se ok com a requisição, trata o resultado (exluido sim ou não)
      request.done(function(resposta) {
        if(resposta==-1){
          alert("A despesa não pode ser excluída!"); //coloca o resultado dentro do container "#log"
          location.reload();
        }else {
          alert("Despesa excluída com sucesso!");
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
        <li class="active" id="myTab"><a href="#tab1" data-toggle="tab">Despesas (Dinheiro)</a></li>
        <li><a href="#tab2" data-toggle="tab">Cadastrar Despesas</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active cadastrado" id="tab1">
          <h2>Despesas no Dinheiro</h2>
          <?php
          $buscaDespesa = $query->sql_query("SELECT * FROM visualizaDespesasDinheiro where Id_User = {$_SESSION['Id']}");
          if(mysqli_num_rows($buscaDespesa)>0){
            echo "<ul id='lista'>";
            while($linha=$buscaDespesa->fetch_assoc()){
              echo "<li style='border-bottom:17px solid ".$linha['Cor']." '>";
              echo "Descrição: ".$linha['Descricao']."<br>";
              echo "Situação: ".$linha['Situacao']."<br>";
              echo "Categoria: ".$linha['NomeCategoria']."<br>";//
              echo "Data: ".$linha['DataTra']."<br>";
              echo "Periodicidade: ".$linha['Periodicidade']."<br>";
              echo "Valor: ".$linha['Valor']."<br>";
              echo "Juros: ".$linha['Juros']."<br>";
              //lembrando: aqui trabalhamos com views,alguns campos foram renomeados na view.
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

                echo "<form action='insereAlteraTransacao.php?acao=altera&codigo=".$linha['IdTransacao']."&tipo=DD' method='POST' class='form-horizontal'>";


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
                echo"<label class='control-label' for='data'>Data de Transação: </label>";
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

                echo"<div class='control-group'>";
                echo"<label class='control-label' for='juros'>Juros ao mês: </label>";
                echo"<div class='controls'>";
                  echo"<input type='text' name='juros' required placeholder='juros' value=".$linha['Juros'].">";
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
            echo "<h3>Não existem despesas no dinheiro cadastradas cadastradas</h3>";
          }
          ?>
        </div>
        <div class="newTransacao  tab-pane" id="tab2">
          <h2> Inserir Nova Despesa</h2><br>
          <div id="formTransacao">
            <form action="insereAlteraTransacao.php?acao=insere&tipo=DD" method="POST" class="form-horizontal">
              <?php require("formDespesas.php"); ?>
            </form>
          </div>
        </div>
      </div>

    </section>
  </body>
  </html>
