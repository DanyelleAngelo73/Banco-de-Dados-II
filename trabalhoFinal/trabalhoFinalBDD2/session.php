<?php
  session_start();
  if(!isset($_SESSION['Id']) || !isset($_SESSION['Email'])) {
   header('location:index.php');
   exit;
  }
  $logado = $_SESSION['Email'];
  $id = $_SESSION['Id'];

 ?>
