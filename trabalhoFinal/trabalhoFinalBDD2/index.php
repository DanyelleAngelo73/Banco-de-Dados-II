<?php
  session_start();
  include_once("Connection.php");
  $query = new Connection();
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="http://localhost/trabalhoFinalBDD2/style/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="http://localhost/trabalhoFinalBDD2/style/style.css" rel="stylesheet">
  <title>Entrar</title>

</head>
<body>
  <section id="container">
    <div id="form-login">
      <form action="" method="POST">
        <div class="control-group">
          <label class="control-label" for="inputEmail">Email</label>
          <div class="controls">
            <input type="text" name="email" required placeholder="Email">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="inputPassword">Senha</label>
          <div class="controls">
            <input type="password" name="password" required placeholder="Senha">
          </div>
        </div>
        <div class="control-group">
          <div class="controls">

            <button type="submit" name='enviar' class="btn btn-inverse btn-large btn-block">Entrar</button>
            <a href="http://localhost/trabalhoFinalBDD2/newUser.php"> <button type="button" class="btn btn-inverse btn-large btn-block">Criar Conta</button></a>
          </div>
        </div>
        <?php
            $resgata = $query->sql_query("SELECT Id FROM Usuario");
            if(mysqli_num_rows($resgata)==0){
              echo "oiaaai";
            }
            if(isset($_POST["email"])&&($_POST["password"])){
              $email = $_POST["email"];
              $password = md5($_POST["password"]);
              $resgata = $query->sql_query("SELECT Id FROM Usuario where Email ='{$email}'");
              if(mysqli_num_rows($resgata)>0){
                $autentica = $query->sql_query("SELECT Id,Email FROM Usuario where Email='{$email}' and Senha='{$password}'");
                if(mysqli_num_rows($autentica)>0){
                  $linha = $autentica->fetch_assoc();//pega a linha do banco correspondente
                  $_SESSION['Id']=$linha['Id'];
                  $_SESSION['Email']=$linha['Email'];
                  header("location:inicio.php");
                  exit;
                }else{
                  unset($_SESSION['Email']);
                  unset($_SESSION['Id']);
                  echo "Senha incorreta";
                }
              }else{
                unset($_SESSION['Email']);
                unset($_SESSION['Id']);
                echo "Usuário não cadastrado!";
              }
            }
         ?>
      </form>
    </div>
  </section>
</body>
</html>
