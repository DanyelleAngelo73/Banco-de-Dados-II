<?php
require("session.php");

include_once("Connection.php");
$query = new Connection();
?>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
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

  <title>Categorias</title>
  <script>
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  })
  function excluir(id){
    if(confirm("Tem certeza que deseja exluir essa categoria?")){
      //pega a variavel, monta o objeto e faz o pedido
      var request = $.ajax({
        url: "excluiCategoria.php",
        method: "POST",
        data: { codigo : id },
        dataType: "json"
      });
      //se ok com a requisição, trata o resultado (exluido sim ou não)
      request.done(function(resposta) {
        if(resposta==-1){
          alert("A categoria não pode ser excluída! Exclua primeiro as transações ligadas a ela, depois exclua a categoria!"); //coloca o resultado dentro do container "#log"
          location.reload();
        }else {
          alert("Categoria excluída com sucesso!");
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
    <div class="tabbable" id="categorias">
      <ul class="nav nav-tabs">
        <li class="active" id="myTab"><a href="#tab1" data-toggle="tab">Categorias</a></li>
        <li><a href="#tab2" data-toggle="tab">Cadastrar Categorias</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active cadastrado" id="tab1">
          <h2>Categorias Cadastradas</h2>
          <?php
          $buscaCategorias= $query->sql_query("SELECT * FROM Categoria where Id_User = {$_SESSION['Id']}");
          if(mysqli_num_rows($buscaCategorias)>0){
            echo "<ul id='lista'>";
            while($linha=$buscaCategorias->fetch_assoc()){
              $cor=$linha['Cor'];
              $id=$linha['Id'];
              $nome=$linha['Nome'];

              echo "<li style='border-bottom:17px solid ".$cor." '>";
              echo "Nome: ".$nome."<br>";
              echo "Código: ".$id."<br>";
              echo "<a class='btn btn-small' href='#' onClick='excluir(".$id.");'><i class='icon-remove'></i></a>";
              //código de edição
              echo "<a href='#myModal".$id."' role='button' class='btn btn-small' data-toggle='modal'><i class='icon-edit'></i></a>";

              echo "<div id='myModal".$id."' class='modal hide fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
                echo "<div class='modal-header'>";
                  echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>";
                  echo "<h3 id='myModalLabel'>Editar ".$nome."</h3>";
                echo "</div>";

                echo "<div class='modal-body'>";
                echo "<form action='insereAlteraCategoria.php?acao=altera&codigo=".$id."' method='POST' class='form-horizontal'>";
                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='nome'>Nome: </label>";
                echo"  <div class='controls'>";
                echo"    <input type='text' name='nome' required  value=".$linha['Nome'].">";
                echo"  </div>";
                echo"</div>";

                echo"<div class='control-group'>";
                echo"  <label class='control-label' for='cor'>Cor: </label>";
                echo "<input type='color'  name='cor' list='cores' value='".$cor."' style='height:40px;'>";
                echo"<datalist id='cores'>";
                echo"<option value='#00c6c9'>Azul</option>";
                echo"<option value='#FF0000'>Vermelho</option>";
                echo"<option value='#FFA500'>Laranja</option>";
                echo"<option value='#FFFF00'>Amarelo</option>";
                echo"<option value='#a6ff05'>Verde</option>";
                echo"<option value='#4B0082'>Indigo</option>";
                echo "<option value='#733e76'>Violeta</option>";
                echo"  </datalist>";
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
            echo "<h3>Não existem categorias cadastradas</h3>";
          }
          ?>
        </div>

        <div class="newCategoria tab-pane" id="tab2">
          <h2> Inserir Nova Categoria</h2><br>
          <form action="insereAlteraCategoria.php?acao=insere" method="POST" class="form-horizontal">
            <?php require("formCategorias.php"); ?>
          </form>
        </div>
    </div>

  </section>
</body>
</html>
