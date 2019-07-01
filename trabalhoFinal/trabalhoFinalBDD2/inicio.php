<?php
require("session.php");
include_once("Connection.php");
$query = new Connection();
$busca= $query->sql_query("SELECT * FROM RelatorioMensal where Id_User = {$_SESSION['Id']} and Mes=8 and Ano=2019");
if(mysqli_num_rows($busca)==0){//não existe relatório cadastrado para esse mês e ano
  $busca= $query->sql_query("SELECT InicioDeMes({$_SESSION['Id']} )");
}
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
  <title>Início</title>
</head>
<body>
  <section id="container">
    <?php require("menu.php"); ?>
  </section>
</body>
</html>
