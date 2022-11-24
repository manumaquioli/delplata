<?php
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
    if(isset($_SESSION['logUsuario'])){
        if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
            //Se cierra sesión y se redirecciona a login.
            session_unset();
            header("location:login.php");
        }
    }
    //Validar filtros.
    if(isset($_POST['busqueda']) && isset($_POST['cat']) && isset($_POST['marca']) && isset($_POST['genero']) && isset($_POST['disciplina']) && isset($_POST['precioInicial']) && isset($_POST['precioFinal']) && isset($_POST['pag'])){
        //Función filtrar (conexionbd.php)
        $arrayProds = filtrar($_POST['cat'], $_POST['marca'], $_POST['disciplina'], $_POST['genero'], $_POST['precioInicial'], $_POST['precioFinal'], $_POST['busqueda'], $_POST['pag']);
        if($arrayProds!="ERROR"){
            if(!empty($arrayProds)){
                foreach($arrayProds as $valor){
                    $nombre = $valor['nomb_prod'];
                    $precio = $valor['precio'];
                    $categoria = $valor['categoria'];
                    $marca = $valor['marca'];
                    $publico = $valor['público'];
                    $stock = $valor['stock'];
                    $descripcion = $valor['descripcion'];
                    $descuento = $valor['descuento'];
                    $img = 'assets/imagenes/'.$valor['img'];
                    $precioFinal = $precio - $descuento*$precio/100;
                    $id = $valor['id_prod'];
                    $maxProds = $valor['c'];
                    //Si la visibilidad del producto es 1, (visible):
                    if($publico == 1){
                        $estructura = "<div class='prods'><a href='assets/enlaces/prod.php?id=$id&cat=$categoria'>
                            <div class='prod-img-container'><img src='$img' alt=''></div>";
                        if($descuento!=0){
                            //Si el descuento es diferente de 0 aparecerá el antiguo precio tachado y al lado el precio incluyendo descuento.
                            $nombrePrecio = "<div class='prod-info'><p>$nombre</p><p>&nbsp &nbsp <del><font color=#c90000>USD$precio</font></del> USD$precioFinal</p>";
                        }
                        else{
                            //Si el descuento es 0, aparecerá solamente el precio final.
                            $nombrePrecio = "<div class='prod-info'><p>$nombre</p><p>USD$precioFinal</p>";
                        }
                        $estructura2 = '</a>';
                        if(!empty($_SESSION['logUsuario']) && $_SESSION['logTipo'] == "cliente"){
                            $estructura3 = "<i class='fa-solid fa-cart-plus' onclick='addToCart($id)' ></i></div>";
                        }else{
                            $estructura3 = "<i class='fa-solid fa-cart-plus' onclick='window.location.href = \"assets/enlaces/login.php\"'></i></div>";
                        }
                        $estructura4 = "</div>";
                        
                        echo $estructura . $nombrePrecio . $estructura2 . $estructura3 . $estructura4;
                    }
            }
            echo "<input id='cantProds' type='hidden' value='$maxProds'>";
        }else{
            echo "<h1 id='mensajeProductos'>No disponemos de ese producto en este momento.</h1>";
        }
        }else{
            echo "ERROR";
        }
    }

    if(isset($_POST['refreshEMP']) && isset($_POST['pag']) && isset($_POST['busqueda'])){
        //Función filtrar (conexionbd.php) solo se le pasa el rango de precios, las palabras clave buscadas y el número de paginado.
        $arrayProds = filtrar(null, null, null, null, 1, 1000000, $_POST['busqueda'], $_POST['pag']);
        //Recorre arrayProds
        foreach($arrayProds as $valor){
            //Guarda variables con la información de la iteración actual del array arrayProds.
            $id = $valor['id_prod'];
            $nombre = $valor['nomb_prod'];
            $precio = $valor['precio'];
            $categoria = $valor['categoria'];
            $marca = $valor['marca'];
            $publico = $valor['público'];
            $stock = $valor['stock'];
            $descripcion = $valor['descripcion'];
            $descuento = $valor['descuento'];
            $img = '../imagenes/'.$valor['img'];
            $precioFinal = $precio - $descuento*$precio/100;
            $maxProds = $valor['c'];

            //Muestra en pantalla toda la información de producto con un formulario para reponer el stock de éste.
            echo  "<form action='' method='post' class='prods'>
                      <div class='prod-img-container'><img src='$img' alt=''></div>";
                if($descuento!=0){
                    //Si el descuento es diferente de 0 aparecerá el antiguo precio tachado y al lado el precio incluyendo descuento.
                  echo "<div class='prod-info'><input type='hidden' name='idProd' value='$id' id='idProdInput$id'><p class='prod-nombre'>$nombre</p><p>&nbsp &nbsp <font color=red><del>USD$precio</del></font> USD$precioFinal</p>";
                  echo "<p id='actualStock$id'>Stock: $stock</p>";
                }
                else{
                    //Si el descuento es 0, aparecerá solamente el precio final.
                  echo "<div class='prod-info'><input type='hidden' name='idProd' value='$id' id='idProdInput$id'><p class='prod-nombre'>$nombre</p><p>USD$precioFinal</p>";
                  echo "<p id='actualStock$id'>Stock: $stock</p>";
                }
                        echo "<input type='number' name='newStock' id='newStockInput$id' placeholder='Cantidad de unidades' required>
                            <input type='hidden' value='$stock' name='stockActual' id='stockActualInput$id'>
                            <label><input type='radio' name='action-type' value='add' id='stockAdd$id'> Agregar </label>
                            <label><input type='radio' name='action-type' value='overwrite' id='stockOverwrite$id'> Sobreescribir </label>
                            <input id='add-stock$id' type='button' value='Agregar stock' class='botonesStock'>";
                            if($publico == 1){
                                echo "<button type='button' id='eye$id' class='visibility-button'><i id='eye-i$id' class='fa-regular fa-eye'></i></button>";
                            }else{
                                echo "<button type='button' id='eye$id' style='color:red;' class='visibility-button'><i id='eye-i$id' class='fa-regular fa-eye-slash'></i></button>";
                            }
                            echo "<p id='mensajesStock$id' class='mensajesStock'>&nbsp</p>
                        </div>
                   </form>";
        }
        if(!isset($maxProds)){echo "<input id='cantProds' type='hidden' value='0'>";}
        else{echo "<input id='cantProds' type='hidden' value='$maxProds'>";}
    }
    //---------------------------------------------------------------------------------------------------
    if(isset($_POST['refreshADM']) && isset($_POST['pag']) && isset($_POST['busqueda'])){
        //Función filtrar (conexionbd.php) solo se le pasa el rango de precios, las palabras clave buscadas y el número de paginado.
        $arrayProds = filtrar(null, null, null, null, 1, 1000000, $_POST['busqueda'], $_POST['pag']);
        foreach($arrayProds as $valor){
            $id = $valor['id_prod'];
            $nombre = $valor['nomb_prod'];
            $precio = $valor['precio'];
            $categoria = $valor['categoria'];
            $marca = $valor['marca'];
            $publico = $valor['público'];
            $stock = $valor['stock'];
            $descripcion = $valor['descripcion'];
            $descuento = $valor['descuento'];
            $img = '../imagenes/'.$valor['img'];
            $precioFinal = $precio - $descuento*$precio/100;
            $maxProds = $valor['c'];

            echo  "<form action='' method='post' class='prods'>
                      <div class='prod-img-container'><img src='$img' alt=''></div>";
                if($descuento!=0){
                    //Si el descuento es diferente de 0 aparecerá el antiguo precio tachado y al lado el precio incluyendo descuento.
                echo "<div class='prod-info'><input type='hidden' class='ids' name='idProd' value='$id'><p class='prod-nombre'>$nombre</p><p>&nbsp &nbsp <font color=red><del>".'U$D'."$precio</del></font>".' U$D'."$precioFinal</p>";
                }
                else{
                    //Si el descuento es 0, aparecerá solamente el precio final.
                echo "<div class='prod-info'><input type='hidden' class='ids' name='idProd' value='$id'><p class='prod-nombre'>$nombre</p><p>".'U$D'."$precioFinal</p>";
                }
                echo "<i class='fa-solid fa-trash trash-btn' onclick='showAlert(". $id .")'></i>

                    <input type='button' class='delProdSubmit' id='delButton$id'>
                        </div>
                    </form>";
        }
            if(!isset($maxProds)){echo "<input id='cantProds' type='hidden' value='0'>";}
            else{echo "<input id='cantProds' type='hidden' value='$maxProds'>";}
    }

    ?>