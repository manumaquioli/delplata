<?php
  try{
    include "conexionbd.php";
}
  catch(Exception $e){
    echo "Error de conexión con la base de datos";
    return;
}
    session_start();
    
    if(isset($_POST['id']) && isset($_SESSION['logTipo']) && $_SESSION['logTipo']=="cliente"){
      if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
        session_unset();
        header("location:../../enlaces/login.php");
      }
      //Función realizar_compra_unica definida en conexionbd.php.
        realizar_compra_unica($connDB, $_POST['id'], $_SESSION['logUsuario']);
    }
    else if (!isset($_SESSION['logTipo'])){
      header("location:../../enlaces/login.php");
    }
?>