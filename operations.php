<?php
  include 'functions/helper.php';
  session_start();

  if($_GET['operation'] == 'logout'){
    session_destroy();
    session_start(); 

    $_SESSION['error'] = 'The session has been ended!';
    $_SESSION['username'] = null;
    $_SESSION['password'] = null;
    header('Location:login.php');
  }

?>