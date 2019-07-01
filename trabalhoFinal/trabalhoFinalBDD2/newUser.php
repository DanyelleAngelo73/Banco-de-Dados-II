<?php
include_once("Connection.php");
$query=new Connection();
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="http://localhost/trabalhoFinalBDD2/style/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="http://localhost/trabalhoFinalBDD2/style/style.css" rel="stylesheet">
  <title>Cadastrar Novo Usuário</title>
</head>
<body>
  <section id="container">
    <div id="form-newUser">
      <h2>Cadastrar novo Usuário</h2>
      <form action="" method="POST" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="email">Email</label>
          <div class="controls">
            <input type="text" name="email" required placeholder="Email">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="nome">Nome</label>
          <div class="controls">
            <input type="text" name="nome" required placeholder="Nome">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="password">Senha</label>
          <div class="controls">
            <input type="password" name="password" required placeholder="Senha">
          </div>
        </div>
        <div class="control-group" id="bottonFormNewUser">

          <a href="javascript:history.back()"><button type="button" class="btn" name="Voltar">Voltar</button></a>
          <button type="reset" class="btn" name="Limpar">Limpar</button>
          <button type="submit" class="btn" name="criar">Criar Conta</button>
        </div>
        <?php
        if(isset($_POST["email"])&&($_POST["nome"])&&($_POST["password"])){
          $email = $_POST["email"];
          $nome = $_POST["nome"];
          $password = md5($_POST["password"]);
          $insertUsuario= $query->sql_query("INSERT INTO Usuario values (null,'{$email}','{$nome}','{$password}',null,null,null,null,null,null,null,null,null)");
          if($query->status==-1){
            echo "Este e-mail já está sendo usado!";
          }
          else if($query->status===1){
            echo "Usuário cadastrado com sucesso!";
          }
        }
        ?>
      </form>
    </div>
  </section>
</body>
</html>
