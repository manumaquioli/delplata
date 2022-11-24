<?php
    include "../php/loginV3/header.php";
    try{
        include "../php/loginV3/conexionbd.php";
    }
    catch(Exception $e){
        echo "Error de conexión con la base de datos";
        return;
    }
    session_start();
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
        <link rel="icon" href="../iconos/Captura.PNG">
        <title>Indumentarias del Plata</title>
        <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../estilos/mis_compras_styles.css">
        <link rel="stylesheet" href="../estilos/header_styles.css">
        <link rel="stylesheet" href="../estilos/footer_styles.css">
    </head>
    <body>
        
        <?php
            //Si está declarado $_SESSION['logUsuario'] y comprobar_susp del $_SESSION['logUsuario'] arroja true se cierra la sesión.
            if(isset($_SESSION['logUsuario']) && comprobar_susp($connDB, $_SESSION['logUsuario'])){
                session_unset();
                header("location:login.php");
            }
            else if(!isset($_SESSION['logUsuario'])){
                header("location:login.php");
            }
            display_header();
            if($_SESSION['logTipo'] != 'cliente'){
                session_unset();
                header("location:login.php");
            }
        ?>

        <div id='parent-container'>
            <?php
                //Función display_compras definida en conexionbd.php
                $compras = display_compras($connDB, $_SESSION['logUsuario']);
                if(empty($compras) || $compras=="ERROR"){
                    echo "<h2>Aún no has realizado ninguna compra.</h2>";
                }else{
                    echo "
                    <table id='compras-container'>
                    <tr id='cabecera'>
                    <th>Código de compra</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    </tr>";
                    foreach($compras as $valor){
                        echo "<tr class='compra-row'>";
                        echo "<td id='first-column'>$valor[id_compra]  
                        <form action='detalle_compra.php' method='post'>
                        <input type='hidden' name='id' value='{$valor['id_compra']}'>
                        <input type='submit' class='btn-detalles' value='Ver detalles'>
                        </form>
                        </td>";
                        echo "<td> ".'U$D '."$valor[monto]</td>";
                        echo "<td>$valor[fecha]</td>";
                        echo "</tr>";
                    }
                }
            ?>
            </table>
        </div>
        
        <?php display_footer() ?>
    <script src="../scripts/mis_compras_script.js"></script>
    
     <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>
    
</body>
</html>