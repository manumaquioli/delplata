<?php
    include "../php/loginV3/header.php";
    
    try{
        include "../php/loginV3/conexionbd.php";
    }
    catch(Exception $e){
        echo "Error de conexión con la base de datos";
        return;
    }
    // session_start();
    ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../iconos/Captura.PNG">
        <title>Indumentarias del Plata</title>
        <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../estilos/prod_styles.css">
        <link rel="stylesheet" href="../estilos/header_styles.css">
        <link rel="stylesheet" href="../estilos/footer_styles.css">
    </head>
<body>
    <?php 

    if(isset($_SESSION['logUsuario']) && $_SESSION['logUsuario'] != null){
        if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
            session_unset();
            header("location:assets/enlaces/login.php");
        }
    }
        display_header();
        if(isset($_GET['id']) && $_GET['id'] != null && $_GET['id'] != ""){
    ?>
    <div id='main-container'>
        <?php
            //Función single_prod definida en conexionbd.php
            $producto = single_prod($connDB);
            if(empty($producto) || $producto == "ERROR" || $producto[0]['categoria'] != $_GET['cat']){
                header("location:../../index.php");
            }
            $id = $producto[0]['id_prod'];
            if(isset($_SESSION['logUsuario'])){
                //Función single_prod definida en conexionbd.php
                loadHistory($connDB, $_SESSION['logUsuario'], $producto[0]['id_prod']);
            }
            foreach($producto as $valor){
                echo "<section id='section-1'><img src='../imagenes/$valor[img]' alt='' id='img-prod'></section>";
            }
        ?>
        <section id='section-2'>
            <h1 id='title'>
            <?php
                foreach($producto as $valor){
                    echo "$valor[nomb_prod]";
                }
            ?>
            </h1>
            <section id="info">
                <?php
                foreach($producto as $valor){
                    echo "<p>$valor[descripcion]</p><br>";
                    if($valor['descuento'] != 0){
                        echo '<font color=#103c8a>Antes: </font>U$D <del><font color=#c90000>'."$valor[precio]</font></del>";
                        echo '<p><font color=#103c8a>Precio actual: </font>U$D '.( ($valor['precio'] * (100-$valor['descuento']) )/100)." &nbsp <b>$valor[descuento]%OFF</b></p>";
                    }else{
                        echo '<p><font color=#103c8a>Precio actual: </font>U$D'." $valor[precio]</p>";
                    }
                    echo "<p><font color=#103c8a>Stock:</font> $valor[stock]</p>";
                    echo "<p><font color=#103c8a>Categoría:</font> $valor[categoria]</p>";
                    switch($valor['genero']){
                        case "M": echo "<p><font color=#103c8a>Género:</font> Mujer</p>";
                            break;
                        case "H": echo "<p><font color=#103c8a>Género:</font> Hombre<p>";
                            break;
                        default: echo "<p><font color=#103c8a>Género:</font> Unisex</p>";
                            break;
                    }
                }
                ?>
            </section>
            <?php echo "
            <form action='../php/loginV3/validarCompras.php' method='post' id='buttons'>
                <input type='hidden' name='id' value='$_GET[id]'>
                <input type='submit' id='buy-btn' value='Comprar'>";
                if(!empty($_SESSION['logUsuario']) && $_SESSION['logTipo'] == "cliente"){
                    echo "<div id='alert'><h2></h2></div>";
                    echo "<script src='../scripts/script_carrito.js'></script>";
                    echo "<button type='button' id='add-cart' onclick='addToCart($id, event)'><i class='fa-solid fa-cart-plus'></i></button>";
                }else{
                    echo "<button type='button' id='add-cart' onclick='window.location.href = \"login.php\"'><i class='fa-solid fa-cart-plus'></i></button>";
                }
            echo "</form>";
            ?>
        </section>
    </div>
    
    <h1 id="intro-slider">También te puede interesar...</h1>
    <div id="prod-slider-container">
        <i class="fa-solid fa-angle-left" id="left-btn"></i>
        <div id="prod-slider">
        <?php
            $contador = 0;
            foreach(prods_by_cat($connDB) as $valor){
                if($contador == 10){
                    break;
                }else{
                if($valor['público'] == 1){
                    $precioFinal = $valor['precio'] - ($valor['descuento']*$valor['precio'])/100;
                    echo "<div class='prods'><a href='prod.php?id=$valor[id_prod]&cat=$valor[categoria]'>
                            <div class='prod-img-container'><img src='../imagenes/$valor[img]' alt=''></div>";
                    if($valor['descuento']!=0){
                        //Si el descuento es diferente de 0 aparecerá el antiguo precio tachado y al lado el precio incluyendo descuento.
                        echo "<div class='prod-info'><p>$valor[nomb_prod]</p><p>&nbsp &nbsp <del><font color=#c90000>USD$valor[precio]</font></del> USD $precioFinal </p>";
                    }
                    else{
                        //Si el descuento es 0, aparecerá solamente el precio final.
                        echo "<div class='prod-info'><p>$valor[nomb_prod]</p><p>USD$precioFinal</p>";
                    }
                    echo '</a>';
                    echo "<i class='fa-solid fa-cart-plus'></i></div>
                    </div>";
                }
                $contador++;
                }
            }
        ?>
        </div>
        <i class="fa-solid fa-angle-right" id='right-btn'></i>
    </div>


    <?php 
        display_footer();
        }else{
            header("location:../../index.php");
        }
    ?>
<script src="../scripts/prod_script.js"></script>

<style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>

</body>


</html>