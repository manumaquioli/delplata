<?php
    try{
        include_once "conexionbd.php";
    }
    catch(Exception $e){
        echo "Error de conexión con la base de datos";
        return;
    }
    session_start();
    $id="";
    $user = $_SESSION['logUsuario'];
    if(!isset($_POST['cart_vaciar']) && !isset($_POST['cart_eliminarProd']) && !isset($_POST['cart_aumentar']) && !isset($_POST['cart_restar']) && !isset($_POST['cart_comprar'])){
        if(isset($_POST['id'])){
            $id = $_POST['id'];    
            //Realizo una consulta para ver si el producto ya se encuentra en el carrito del usuario.
            $sql = mysqli_query($connDB, "SELECT * FROM guarda WHERE nomb_usu = '$user'");
            $arrayProds = array();
            while($fila = mysqli_fetch_array($sql)){
                $arrayProds[] = $fila;
            }

            $prodEnCart = 0;
            foreach($arrayProds as $value){
                if($value['id_prod'] == $id){
                    $prodEnCart = 1;
                    break;
                }
            }
            if($prodEnCart){
                $cantidad = $value['cantidad']+1;
                $update = "UPDATE guarda SET cantidad = '$cantidad' WHERE id_prod = '$id'";
                if($connDB->query($update) === TRUE){
                    echo "Se agregó 1. Total: $cantidad";
                }else{
                    echo "Ocurrió un error.";
                }
            }else{
                $insercion = "INSERT INTO guarda VALUES('$user', '$id', '1')";
                if($connDB->query($insercion) === TRUE){
                    echo "Se agregó el producto al carrito.";
                }else{
                    echo "Ocurrió un error";
                }
            }
        }
    }
    else if(isset($_POST['cart_vaciar'])){
        $status = 0;
        $sqlVaciarCarrito = "DELETE FROM guarda WHERE nomb_usu='$user'";
        if($connDB->query($sqlVaciarCarrito) === TRUE){
            $status = 1;
        }
        else{
            $status = 0;
        }
        //Función carrito_calcular_precio definida en conexionbd.php
        $precioFinal = carrito_calcular_precio($connDB, $user);
        $rsp = array(
            "status" => $status,
            "precioFinal" => $precioFinal
        );
        echo json_encode($rsp);
    }
    
    else if(isset($_POST['cart_eliminarProd'])){
        $status = 0;
        $sqlEliminarProd = "DELETE FROM guarda WHERE nomb_usu='$user' AND id_prod='{$_POST['idEliminar']}'";
        if($connDB->query($sqlEliminarProd) === TRUE){
            $status = 1;
        }
        else{
            $status = 0;
        }
        //Función carrito_calcular_precio definida en conexionbd.php
        $precioFinal = carrito_calcular_precio($connDB, $user);
        $rsp = array(
            "status" => $status,
            "precioFinal" => $precioFinal
        );
        echo json_encode($rsp);
    }
    else if(isset($_POST['cart_aumentar'])){
        $status = 0;
        $sqlAumentar = "UPDATE guarda SET cantidad=cantidad+1 WHERE nomb_usu='$user' AND id_prod='{$_POST['idAumentar']}'";
        if($connDB->query($sqlAumentar) === TRUE){
            $status = 1;
        }
        else{
            $status = 0;
        }
        $sqlCant = "SELECT cantidad FROM guarda WHERE nomb_usu='$user' AND id_prod='{$_POST['idAumentar']}'";
        $cantProd = mysqli_query($connDB, $sqlCant);
        $cantRow = array();
        while($fila = mysqli_fetch_array($cantProd)){
            $cantRow[] = $fila;
        }
        //Función carrito_calcular_precio definida en conexionbd.php
        $precioFinal = carrito_calcular_precio($connDB, $user);
        $rsp = array(
            "cantidad" => $cantRow[0]['cantidad'],
            "status" => $status,
            "precioFinal" => $precioFinal
        );
        echo json_encode($rsp);
    }

    else if(isset($_POST['cart_restar'])){
        $status = 0;
        $sqlRestar = "UPDATE guarda SET cantidad=cantidad-1 WHERE nomb_usu='$user' AND id_prod='{$_POST['idRestar']}'";
        if($connDB->query($sqlRestar) === TRUE){
            $status = 1;
        }
        else{
            $status = 0;
        }
        
        $sqlCant = "SELECT cantidad FROM guarda WHERE nomb_usu='$user' AND id_prod='{$_POST['idRestar']}'";
        $cantProd = mysqli_query($connDB, $sqlCant);
        $cantRow = array();
        while($fila = mysqli_fetch_array($cantProd)){
            $cantRow[] = $fila;
        }
        $cantidad = $cantRow[0]['cantidad'];

        if($cantidad <= 0){
            $sqlEliminarProd = "DELETE FROM guarda WHERE nomb_usu='$user' AND id_prod='{$_POST['idRestar']}'";
            if($connDB->query($sqlEliminarProd) === TRUE){
                $status = 2;
            }
        }
        //Función carrito_calcular_precio definida en conexionbd.php
        $precioFinal = carrito_calcular_precio($connDB, $user);
        $rsp = array(
            "cantidad" => $cantidad,
            "status" => $status,
            "precioFinal" => $precioFinal
        );
        echo json_encode($rsp);
    }
    
    else if(isset($_POST['cart_comprar'])){
        //Función realizar_compra_cart definida en conexionbd.php
        $status = realizar_compra_cart($connDB, $_SESSION['logUsuario']);
        if($status==1){
            $sqlVaciarCarrito = "DELETE FROM guarda WHERE nomb_usu='$user'";
            mysqli_query($connDB, $sqlVaciarCarrito);
        }
        $rsp = array(
            "status" => $status,
        );
        echo json_encode($rsp);
    }
?>