  <?php
    require("session.php");
    require_once("Connection.php");
    $query=new Connection();
    $exclui= $query->sql_query("DELETE FROM Categoria WHERE Id= {$_POST['codigo']} and Id_User={$_SESSION['Id']}");
    echo $query->status;
  ?>
