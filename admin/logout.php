<?php 
    session_start(); //to ensure you are using same session
   
    unset($_SESSION['admin']); //destroy the session
    header("location:../login.php"); //to redirect back to "index.php" after logging out
    exit();

?>