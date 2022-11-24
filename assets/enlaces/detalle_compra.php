<?php
    include "../php/loginV3/header.php";
    include "../php/loginV3/clases/compra.php";
    try{
        include "../php/loginV3/conexionbd.php";
    }
    catch(Exception $e){
        echo "Error de conexión con la base de datos";
        return;
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../iconos/Captura.PNG">
    <title>Compra - Indumentarias del Plata</title>
    <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/detalle_compra_styles.css">
    <link rel="stylesheet" href="../estilos/header_styles.css">
    <link rel="stylesheet" href="../estilos/footer_styles.css">
</head>
<body>
    <?php
        if(!isset($_SESSION['logUsuario'])){session_start();}
        if(isset($_SESSION['logUsuario']) && comprobar_susp($connDB, $_SESSION['logUsuario'])){
            session_unset();
            header("location:login.php");
        }
        else if(!isset($_SESSION['logUsuario'])){
            header("location:login.php");
        }

        // display_header();
        if($_SESSION['logTipo'] != 'cliente'){
            session_unset();
            header("location:login.php");
        }
    ?>

    <link rel='stylesheet' href='../estilos/boleta_styles.css'>
    <div id='boleta-container'>
        <section id="boleta-empresa-section">
            <div>
                <b>
                    <p>Indumentarias del Plata S.R.L.</p>
                    <p>Mones Roses 6633</p>
                    <p>Tel 43721471</p>
                </b>
            </div>
            <div>
                <b>
                    <p>R.U.T. 065024561241</p>
                    <p>Contado</p>
                    <p>Serie A N° 0001</p>
                </b>
            </div>
        </section>
        <section id="boleta-fecha-section">
            <table id='fecha-section-table1'>
                <tr>
                    <th>RUT COMPRADOR</th>
                    <th>CONSUMO FINAL</th>
                </tr>
                <tr>
                    <td>054625789451</td>
                    <td>-</td>
                </tr>
            </table>
            <table id='fecha-section-table2'>
                <?php
                    if(isset($_POST['id'])){
                        //Si la compra pasada por POST[id] no pertenece al usuario de la sesión se devolverá a mis_compras.php.
                          //Función verificar_comprador definida en conexionbd.php.
                        $comprador = verificar_comprador($connDB, $_POST['id']);
                        if(!is_array($comprador) || $comprador['nomb_usu']!=$_SESSION['logUsuario']){
                            header("location:mis_compras.php");
                        }
                        $compraRow = info_compras($connDB, $_POST['id']);
                        $compra = new Compra(null, null, null, null);
                    }else{
                        header("location:mis_compras.php");
                    }
                    $subTotal = 0;
                    foreach($compraRow as $valor){
                        $fecha = explode("-", $valor['fecha']);
                        $montoTotal = $valor['monto'];
                        $prod = $valor['nomb_prod'];
                        if(!isset($valor['descuento']) || $valor['descuento']==0){
                            $precioProd = $valor['precio'];
                        }else{
                            $precioProd = $valor['precio'] -= $valor['precio']*$valor['descuento']/100;
                        }
                        $cantidad = $valor['cantidad'];
                        $idProd = $valor['id_prod'];
                        $prodCat = $valor['categoria'];
                        $subTotal += $valor['precio']*78/100;
                        /*SETEO LOS PRODUCTOS DENTRO DEL FOREACH YA QUE EL ARREGLO 
                        $compraRow TIENE TANTOS ÍNDICES COMO PRODUCTOS TENGA LA COMPRA*/
                        $compra->setProds($prod, $precioProd, $cantidad, $idProd, $prodCat);
                    }
                    $compra->setMontoTotal($montoTotal);
                ?>
                <tr>
                    <th>DÍA</th>
                    <th>MES</th>
                    <th>AÑO</th>
                </tr>
                <tr>
                    <td><?php echo $fecha[2] ?></td>
                    <td><?php echo $fecha[1] ?></td>
                    <td><?php echo $fecha[0] ?></td>
                </tr>
            </table>
        </section>
        <section id="boleta-cliente-section">
            <p>
                <b>Nombre:</b>
                <?php
                    $nombre = getUser($connDB, $_SESSION['logUsuario'])[0]['nombre'];
                    $apellido = getUser($connDB, $_SESSION['logUsuario'])[0]['apellido'];
                    echo $nombre." ".$apellido;
                ?>
            </p>
            <p><b>Domicilio:</b> - </p>
        </section>
        <table id='boleta-table-section'>
            <tr>
                <th>CANTIDAD</th>
                <th>DETALLE</th>
                <th>P. UNITARIO TOTAL (U$D)</th>
            </tr>
            <?php
                
                    foreach($compra->getProds() as $prod){
                        echo "<tr>
                                <td>".$prod[2]."</td>
                                <td>".$prod[0]."</td>
                                <td>".($prod[1]*78/100)."</td>
                            </tr>";
                    }
            ?>
            <tr>
                <td colspan='2' align='right'><b>SUBTOTAL:</b></td>
                <td><b><?php echo $subTotal ?></b></td>
            </tr>
            <tr>
                <td colspan='2' align='right'><b>I.V.A. 22%:</b></td>
                <td><b><?php echo $compra->getMontoTotal()*22/100; ?></b></td>
            </tr>
            <tr>
                <td colspan='2' align='right'><b>TOTAL:</b></td>
                <td><b><?php echo $compra->getMontoTotal(); ?></b></td>
            </tr>
        </table>
        <section id="boleta-info-section">
            <div>
                <b>
                    <p>IMPRENTA LTDA.</p>
                    <p>042350487452</p>
                    <p>CONSTANCIA 000000001</p>
                    <p>FECHA 11/2022</p>
                    <p>FACTURA A 0001/1000 - 2 VÍAS</p>
                    <p>FECHA VENCIMIENTO 27 - 02 - 2025</p>
                    <p>IMPRENTA AUTORIZADA</p>
                </b>
            </div>
            <div>
                <b>
                    <p>ORIGINAL</p>
                    <p>CLIENTE</p>
                </b>
            </div>
        </section>
    </div>

    <?php //display_footer(); ?>
    <script src="../scripts/detalle_compra_script.js"></script>
    
    <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
    </style>
    <div id="ult"></div>
    
</body>
</html>