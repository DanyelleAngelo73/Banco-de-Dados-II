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
  <title>Cartão</title>
  <script>
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  })
  function excluir(id){
    if(confirm("Tem certeza que deseja exluir esse cartão?")){
      //pega a variavel, monta o objeto e faz o pedido
      var request = $.ajax({
        url: "excluiCartoes.php",
        method: "POST",
        data: { codigo : id},
        dataType: "json"
      });
      //se ok com a requisição, trata o resultado (exluido sim ou não)
      request.done(function(resposta) {
        if(resposta==-1){
          alert("O cartão não pode ser excluído!Exclua as despesas associadas a ele e tente novamente!"); //coloca o resultado dentro do container "#log"
          location.reload();
        }else {
          alert("Cartão excluído com sucesso!");
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
    <div class="tabbable" id="cartao"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
        <li class="active" id="myTab"><a href="#tab1" data-toggle="tab">Cartões</a></li>
        <li><a href="#tab2" data-toggle="tab">Cadastrar Cartões</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active cadastrado" id="tab1">
          <h2>Cartões Cadastrados</h2>
          <?php
          $buscaCartao= $query->sql_query("SELECT * FROM Cartao where Id_User = {$_SESSION['Id']}");
          if(mysqli_num_rows($buscaCartao)>0){
            echo "<ul id='lista'>";
            while($linha=$buscaCartao->fetch_assoc()){
              echo "<li>";
              echo "Nome do cartão: ".$linha['Nome']."<br>";
              echo "Bandeira: ".$linha['Bandeira']."<br>";
              echo "Limite: R$".$linha['Limite']."<br>";
              echo "Fechamento da Fatura: ".$linha['DataFechamento']."<br>";
              echo "Pagamento da Fatura: ".$linha['DataPagamento']."<br>";
              echo "<a class='btn btn-small' href='#' onClick='excluir(".$linha['Id'].");'><i class='icon-remove'></i></a>";
              //código de edição
              echo "<a href='#myModal".$linha['Id']."' role='button' class='btn btn-small' data-toggle='modal'><i class='icon-edit'></i></a>";

              echo "<div id='myModal".$linha['Id']."' class='modal hide fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
                echo "<div class='modal-header'>";
                  echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>";
                  echo "<h3 id='myModalLabel'>Editar ".$linha['Nome']."</h3>";
                echo "</div>";
                echo "<div class='modal-body'>";
                echo "<form action='insereAlteraCartao.php?acao=altera&codigo=".$linha['Id']."' method='POST' class='form-horizontal'>";
                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='nome'>Nome: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='nome' required  value=".$linha['Nome'].">";
                echo"  </div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='bandeira'>Bandeira: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='bandeira' required  value=".$linha['Bandeira'].">";
                echo"  </div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='limite'>Limite: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='limite' required  value=".$linha['Limite'].">";
                echo"  </div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='fechamento'>Fechamento da fatura: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='fechamento' required  value=".$linha['DataFechamento'].">";
                echo"  </div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='vencimento'>Data de Pagamento: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='vencimento' required  value=".$linha['DataPagamento'].">";
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
            echo "<h3>Não existem cartões cadastrados</h3>";
          }
          ?>
        </div>
        <div class="newCartao tab-pane" id="tab2">
          <h2> Inserir Novo Cartao</h2><br>
          <div id="formCartao">
            <form action="insereAlteraCartao.php?acao=insere" method="POST" class="form-horizontal">
              <?php require("formCartao.php"); ?>
            </form>
          </div>
        </div>
      </div>

    </section>
  </body>
  </html>
