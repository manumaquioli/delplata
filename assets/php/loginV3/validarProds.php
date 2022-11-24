<?php
    include "clases/productos.php";
    try{
        include_once "conexionbd.php";
    }
    catch(Exception $e){
        echo "Error de conexión con la base de datos";
        return;
    }
    //Se inicia la sesión
    session_start();
    $GLOBALS['vacio']=0;
    //Si el usuario está suspendido
    if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
        //Se cierra sesión y se redirecciona a login.
        session_unset();
        header("location:login.php");
    }
    //AGREGAR NUEVO PRODUCTO.
    //Si se encuentra en el POST las variables nuevoProdNom, nuevoProdPrecio y nuevoProdImg
    if(isset($_POST['nuevoProdNom']) && isset($_POST['nuevoProdPrecio']) && isset($_POST['nuevoProdImg'])){
    //Verifica que no hayan campos vacíos.
            if($_POST['nuevoProdNom'] != "" && $_POST['nuevoProdPrecio'] != "" && $_POST['nuevoProdCat'] != "" &&
                $_POST['nuevoProdGenero'] != "" && $_POST['nuevoProdMarca'] != "" &&
                $_POST['nuevoProdVisibilidad'] != "" && $_POST['nuevoProdStock'] != "" && $_POST['nuevoProdDescuento'] != "" &&
                $_POST['nuevoProdDescripcion'] != "" && $_POST['nuevoProdImg'] != "" && !empty($_FILES['imagenProd']) && $_POST['nuevoProdMinStock'] != ""){
                //Si no hay campos vacíos instancia el nuevo producto con la información ingresada en el formulario.
                $producto = new Producto($_POST['nuevoProdNom'], $_POST['nuevoProdPrecio'], $_POST['nuevoProdCat'], $_POST['nuevoProdGenero'], $_POST['nuevoProdSubCat'], $_POST['nuevoProdMarca'], $_POST['nuevoProdVisibilidad'], $_POST['nuevoProdStock'], $_POST['nuevoProdDescuento'], $_POST['nuevoProdDescripcion'], 0, $_POST['nuevoProdImg'], $_POST['nuevoProdMinStock']);
                $producto->setImg($_POST['nuevoProdImg']);
                $producto->ingresarProdBd();
            }else{
                //Si hay algún campo vacío asigna a la variable global "vacio" el valor 1.
                $GLOBALS['vacio']=1;
            }
            //Crea un array con el valor de las variables globals "vacío", "prohibidos" y "errorCarga".
                $rsp = array(
                    'vacio' => $GLOBALS['vacio'],
                    'prohibidos' => $GLOBALS['prohibidos'],
                    'errorCarga' => $GLOBALS['errorCarga'],
                    'precioNegativo' => $GLOBALS['precioNegativo'],
                    'errorVisibilidad' => $GLOBALS['errorVisibilidad'],
                    'stockNegativo' => $GLOBALS['stockNegativo'],
                    'minStockNegativo' => $GLOBALS['minStockNegativo'],
                    'descuentoInvalido' => $GLOBALS['descuentoInvalido'],
                    'compradosNegativo' => $GLOBALS['compradosNegativo']
                );
                //Codifica el array en formato JSON.
                    $resp = json_encode($rsp);
                    //Lo muestra en pantalla para que Ajax lo tome como respuesta.
                    echo $resp;                    
    }
     ////-------------------------------------------------------------------------------------------------------------------------------------
        // //ELIMINAR UN PRODUCTO QUE NO FUE VENDIDO
        if(isset($_POST['eliminarProd']) && $_POST['eliminarProd']==1){
            $prodEliminado = 0;
            $id_prod = mysqli_real_escape_string($connDB, $_POST['idProd']);

            $prod = getProd($connDB, $id_prod);
            $comprado = $prod[0]["comprados"];
            if($comprado == 0){
                $sql = "DELETE FROM producto WHERE id_prod=$id_prod";
                try{
                    mysqli_query($connDB, $sql);
                    $prodEliminado = 1;
                    }
                catch(Exception $e){
                        // echo "ERROR CON LA BASE DE DATOS";
                        $prodEliminado = 0;
                    }
            }else{
                $prodEliminado = 2;
            }
            $resp = array(
            "status" => $prodEliminado,
        );
        echo json_encode($resp);
        }
//--------------------------------------------------------------------------------------------------------------------------------------------
        //MODIFICAR STOCK DE UN PRODUCTO
        //Si se encuentra reponerStock en el POST
        if(isset($_POST['reponerStock'])){
            //Se crea la variable global actualizarStock con valor 0.
            $GLOBALS['actualizarStock']=0;
            //Si está en el POST idProd, newStock y actionType:
            if(isset($_POST['idProd']) && isset($_POST['newStock']) && isset($_POST['actionType'])){
                //Variable id_prod, contendrá la id del producto para modificar stock pasada por POST.
                $id_prod = $_POST['idProd'];
                //Consulta para traer el stock del producto con una id determinada.
                $stockProd = mysqli_query($connDB, "SELECT stock FROM producto WHERE id_prod='$id_prod'");
                $arrayStocks = array();
                while($fila = mysqli_fetch_array($stockProd)){
                    $arrayStocks[] = $fila;
                }
                //actualStock guardará el stock del producto obtenido de la consulta.
                $actualStock = $arrayStocks[0]['stock'];
                
                //Si la acción seleccionada en los checkbox fue la de añadir, se sumará a actualStock el nuevo stock obtenido por POST.
                if($_POST['actionType'] == "add"){
                    $stock = $actualStock + $_POST['newStock'];
                //Si la acción seleccionada en los checkbox fue la de sobreescribir, el stock será el ingresado en el formulario y no la suma de ese y el actual.
                }else if($_POST['actionType'] == "overwrite"){
                    $stock = $_POST['newStock'];
                }
                //Se almacena la fecha en la variable fecha.
                $fecha = date("Y-m-d");
                //Si el stock final es mayor al actual, la acción será "Agregó stock", en cambio si el stock final es menor la acción será "Quitó stock".
                $accion="-";
                $stock = intval($stock);
                if($stock > $actualStock){
                    $accion="Agregó stock";
                }
                else if($stock < $actualStock){
                    $accion = "Quitó stock";
                }
                //Se guardan las consultas en dos variables, sqlProd y sqlGestiona.
                $sqlProd = "UPDATE producto SET stock='$stock' WHERE id_prod='$id_prod'";
                $sqlGestiona ="INSERT INTO gestiona (fecha, accion, info, nomb_usu, id_prod) VALUES ('$fecha', '$accion', 'Antiguo: $actualStock, Nuevo: $stock', '{$_SESSION['logUsuario']}', $id_prod)";
                //Se implementa un Try Catch para que en caso de error no sea mostrado en pantalla, sea atrapado solamente.
                try{
                    //Si las consultas tienen éxito, se guarda 1 en la variable global actualizarStock
                    if($connDB->query($sqlProd) === TRUE && $connDB->query($sqlGestiona) === TRUE){
                        $GLOBALS['actualizarStock'] = 1;
                    //En caso de no tener éxito se guarda 0.
                    }else{
                        $GLOBALS['actualizarStock'] = 0;
                    }
                }
                catch(Exception $e){
                    $GLOBALS['actualizarStock'] = 0;
                }     
            }else{
                $GLOBALS['actualizarStock'] = 0;
            }
            //Array para enviar como response a ajax.
            $resp = array(
                //Stock llevará el nuevo stock.
                "stock" => $stock,
                //Status, 1:bien, 2:mal.
                "status" => $GLOBALS['actualizarStock']
            );
            echo json_encode($resp);
        }
        //---------------------------------------------------------------------------------------------------------------------------------------
        if(isset($_POST['buscarProdModificar'])){
            $status = 1;
            if(isset($_POST['id']) && $_POST['id']!=null && $_POST['id']>=0){
                //Función getProd definida en conexionbd.php 
                $prod = getProd($connDB, $_POST['id']);
                if($prod==null || $prod[0]['nomb_prod']==null && $prod[0]['precio']==null){
                    $status = 0;
                }  
            }else{
                $status = 0;
            }
            if($status==1){
                $rsp = array(
                    "status" => $status,
                    "nombre" => $prod[0]['nomb_prod'],
                    "precio" => $prod[0]['precio'],
                    "categoria" => $prod[0]['categoria'],
                    "descuento" => $prod[0]['descuento'],
                    "descripcion" => $prod[0]['descripcion'],
                    "min_stock" => $prod[0]['min_stock'],
                );
            }else if($status==0){
                $rsp = array(
                    "status" => $status,
                );
            }
            echo json_encode($rsp);
        }

        if(isset($_POST['productoModificar'])){
            $status = 0;
            if(isset($_POST['productoModificar']) && $_POST['productoModificar']!=null && $_POST['productoModificar']>0){
                if($_POST['nombProd']!="" && $_POST['nombProd']!=null && $_POST['precioProd']!=null && $_POST['precioProd']!="" && $_POST['descripcion']!=null && $_POST['descripcion']!="" && $_POST['minStock']!="" && $_POST['minStock']!=null){
                    //Función getProd definida en conexionbd.php 
                    $prod = getProd($connDB, $_POST['productoModificar']);
                    $prodActualObj = new Producto($prod[0]['nomb_prod'], $prod[0]['precio'], $prod[0]['categoria'], $prod[0]['genero'], $prod[0]['subcategoria'], $prod[0]['marca'], $prod[0]['público'], $prod[0]['stock'], $prod[0]['descuento'], $prod[0]['descripcion'], $prod[0]['comprados'], $prod[0]['min_stock']);
                    $prodActualObj->setNomb($_POST['nombProd']);
                    $prodActualObj->setPrecio($_POST['precioProd']);
                    $prodActualObj->setCat($_POST['categoria']);
                    if($_POST['descuento']!=null){
                        $prodActualObj->setDescuento($_POST['descuento']);
                    }
                    else if($_POST['descuento']==null){
                        $prodActualObj->setDescuento(0);
                    }
                    $prodActualObj->setDescripcion($_POST['descripcion']);
                    if($_FILES['imagenProdMod']!=null && $_POST['prodModImg']!=null && $_POST['prodModImg']!="" && strlen($_POST['prodModImg'])>0){
                        $prodActualObj->setImgMod($_POST['prodModImg']);
                    }
                    $prodActualObj->setMinStock($_POST['minStock']);
                    //Método updateProdBd de producto.
                    $prodActualObj->updateProdBd($_POST['productoModificar']);
                    $status=1;
                }
                else{
                    $status = 0;
                    $GLOBALS['vacio']=1;
                }
            }else{
                $status = 0;
                $GLOBALS['vacio'] = 1;
            }
            $rsp = array(
                'vacio' => $GLOBALS['vacio'],
                'prohibidos' => $GLOBALS['prohibidos'],
                'errorCarga' => $GLOBALS['errorCarga'],
                'precioNegativo' => $GLOBALS['precioNegativo'],
                'minStockNegativo' => $GLOBALS['minStockNegativo'],
                'descuentoInvalido' => $GLOBALS['descuentoInvalido'],
            );
            echo json_encode($rsp);
        }
        if(isset($_POST['idProdOcultar'])){
            $id = $_POST['idProdOcultar'];
            $status = null;
            $visibilidad = null;
            $visibilidadActual = consulta_prod_visibilidad($connDB, $id);
            if($visibilidadActual[0]['público']=="0"){
                try{
                    if($connDB->query("UPDATE producto SET público=1 WHERE id_prod=$id")){
                        $visibilidad = 1;
                        $fecha = date("Y-m-d");
                        if($connDB->query("INSERT into gestiona VALUES(null, '$fecha', null, 'Publicó', '{$_SESSION['logUsuario']}', $id)")){
                        $status = 1;
                        }
                    }       
                }
                catch(Exception $e){
                    $status = 0;
                }
            }
            if($visibilidadActual[0]['público']=="1"){
                try{
                    if($connDB->query("UPDATE producto SET público=0 WHERE id_prod=$id")){
                        $visibilidad = 0;
                        $fecha = date("Y-m-d");
                        if($connDB->query("INSERT into gestiona VALUES(null, '$fecha', null, 'Ocultó', '{$_SESSION['logUsuario']}', $id)")){
                        $status = 1;
                        }
                    }
                }
                catch(Exception $e){
                    $status = 0;
                }
            }
            $rsp = array(
                "status" => $status,
                "visibilidad" => $visibilidad
            );
            echo json_encode($rsp);
        }
        if(isset($_POST['prodAlerts'])){
            try{
                $nots = alertas($connDB);
                if(!empty($nots)){
                    foreach($nots as $not){
                        $nombre = $not['nomb_prod'];
                        $id = $not['id_prod'];
                        $stock = $not['stock'];
                        $minStock = $not['min_stock'];
                        if($stock < $minStock){
                            echo "<li><p><font color=red>$id:$nombre <br> Stock: $stock</font></p></li>";
                        }elseif($stock == $minStock){
                            echo "<li><p><font color=orange>$id:$nombre <br> Stock: $stock</font></p></li>";
                        }
                    }
                }else{
                    echo "<li><p>No tienes notificaciones.</p></li>";
                }
            }catch(Exception $e){
                echo "<li><p>ERROR</p></li>";
            }
        }
    ?>