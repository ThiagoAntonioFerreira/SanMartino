<?php
  session_start();
  error_reporting(E_ALL); ini_set('display_errors', 'On'); 
  unset ($_SESSION['email']);
  unset ($_SESSION['nome']);
  unset ($_SESSION['id_usuario']);
  session_destroy();
  header('location:login.php');
?>