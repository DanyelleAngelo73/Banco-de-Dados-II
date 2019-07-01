<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="http://localhost/trabalhoFinalBDD2/style/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="http://localhost/trabalhoFinalBDD2/style/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="http://localhost/trabalhoFinalBDD2/style/style.css" rel="stylesheet">
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
  <script type="text/javascript" src="https://globocom.github.io/bootstrap/assets/js/bootstrap-tab.js"></script>

  <title>Categorias</title>
<body>
  <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home">Início</a></li>
    <li><a href="#profile">Perfil</a></li>
    <li><a href="#messages">Mensagens</a></li>
    <li><a href="#settings">Configurações</a></li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="home">.c.</div>
    <div class="tab-pane" id="profile">.o.</div>
    <div class="tab-pane" id="messages">..a</div>
    <div class="tab-pane" id="settings">.b.</div>
  </div>

  <script>
  $('#myTab a').click(function (e) {
e.preventDefault();
$(this).tab('show');
})
  </script>
</body>
</html>
