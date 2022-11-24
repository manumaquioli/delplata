<?php
global $connDB;
//conexión BD
//------------------------------LOCAL--------------------------
$pass="root";
if($_SERVER['DOCUMENT_ROOT'][3]=="x"){
    $pass = ""; 
}
$hostname = "localhost";
$dbname = "indumentarias_del_plata2";
$username_db = "root";
$password_db = "$pass";
//-----------------------------UTU-----------------------------
// $hostname = "localhost";
// $dbname = "dbemt3grp01";
// $username_db = "equipo01";
// $password_db = "3quip0_UNO";
//-----------------------------HOST----------------------------
// $hostname = "localhost";
// $dbname = "id19175488_indumentarias_del_plata";
// $username_db = "id19175488_cubik";
// $password_db = "io|h1Mi8<fo-O@!J";
//-------------------------------------------------------------

$connDB = new mysqli($hostname, $username_db, $password_db, $dbname);

$sql =("SET NAMES 'utf8'");
if ($connDB->query($sql) === TRUE) {
} 
else {
        echo "<h1>ERROR CON LA BASE DE DATOS</h1>";
}
if (!$connDB) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

function single_prod($connDB){
            $consulta = "SELECT * FROM producto WHERE id_prod=$_GET[id]";
            try{
                // $allProd = mysqli_query($connDB, $consulta);
                if($allProd = $connDB->query($consulta)){
                    $prodRow = array();
                    while($fila = mysqli_fetch_array($allProd)){
                        $prodRow[] = $fila;
                    }
                    return $prodRow;
                }
            }
            catch(Exception $e){
                return "ERROR";
            }
    }

function prods_by_cat($connDB){
    $consulta = "SELECT * FROM producto WHERE categoria='$_GET[cat]' and id_prod!=$_GET[id]";
    try{
        $allProd = mysqli_query($connDB, $consulta);
    }
    catch(Exception $e){
        return "ERROR";
    }       
    $prodRow = array();
    while($fila = mysqli_fetch_array($allProd)){
        $prodRow[] = $fila;
    }
    return $prodRow;
}

function display_compras($connDB, $usuario){
    $consulta = "SELECT * FROM compra WHERE nomb_usu='$usuario' ORDER BY id_compra DESC";
    try{
        $allCompra = mysqli_query($connDB, $consulta);
    }
    catch(Exception $e){
        return "ERROR";
    }
    $compraRow = array();
    while($fila = mysqli_fetch_array($allCompra)){
        $compraRow[] = $fila;
    }
    return $compraRow;
}

function comprobar_susp($connDB, $usuario){
    $consulta = "SELECT suspendido FROM usuario WHERE nomb_usu='$usuario'";
    try{
        $suspUsuario = mysqli_query($connDB, $consulta);
    }
    catch(Exception $e){
        return "ERROR";
    }
    $suspUsuarioArray = array();
    while($fila = mysqli_fetch_array($suspUsuario)){
        $suspUsuarioArray[] = $fila;
    }
    if($suspUsuarioArray!=null){
        $susp = $suspUsuarioArray[0]['suspendido'];
        return $susp;
    }
    else{
        return 0;
    }
}
function info_compras($connDB, $id){
    //OBTIENE DATOS DE *UNA* COMPRA HECHA POR UN USUARIO EN PARTICULAR
    //(devuelve tantas tuplas como productos haya en la compra)
    $consulta = "SELECT * FROM v_info_compras WHERE id_compra = $id";
    try{
        $allCompra = mysqli_query($connDB, $consulta);
    }
    catch(Exception $e){
        return "ERROR";
    }
    $compraRow = array();
    while($fila = mysqli_fetch_array($allCompra)){
        $compraRow[] = $fila;
    }
    return $compraRow;
}

function filtrar($cat, $marca, $disciplina, $genero, $precioInicial, $precioFinal, $busqueda, $pag){
    global $connDB;
    $busqueda = mysqli_real_escape_string($connDB, $busqueda);
    $pag = mysqli_real_escape_string($connDB, $pag);
    $cat = mysqli_real_escape_string($connDB, $cat);
    $marca = mysqli_real_escape_string($connDB, $marca);
    $genero = mysqli_real_escape_string($connDB, $genero);
    $precioInicial = mysqli_real_escape_string($connDB, $precioInicial);
    $precioFinal = mysqli_real_escape_string($connDB, $precioFinal);

    $precioInicial = floatval($precioInicial);
    $precioFinal = floatval($precioFinal);
    $pag = intval($pag);

    if($pag<=0 || $pag === null){$pag=1;}
    $paginadoInicial = 0;
    if($pag!=1){
        $paginadoInicial=($pag-1)*15;
    }
    if($cat == null){$cat = "";}
    if($busqueda == null){$busqueda=="";}
    if($marca == null){$marca=="";}
    if($disciplina == null){$disciplina=="";}
    if($genero == null){$genero=="";}
    if($genero!="H" && $genero!="M"){$genero="";}
    if($disciplina == ""){
        $consulta = "SELECT * FROM
                    (
                    SELECT count(id_prod) c FROM producto
                    WHERE nomb_prod LIKE '%$busqueda%' AND precio-descuento*precio/100 BETWEEN $precioInicial and $precioFinal AND categoria LIKE '%$cat%' AND genero LIKE '%$genero%' AND marca LIKE '%$marca%' AND (subcategoria LIKE '%$disciplina%' OR subcategoria is null)) a, producto
                    WHERE nomb_prod LIKE '%$busqueda%' AND precio-descuento*precio/100 BETWEEN $precioInicial and $precioFinal AND categoria LIKE '%$cat%' AND genero LIKE '%$genero%' AND marca LIKE '%$marca%' AND (subcategoria LIKE '%$disciplina%' OR subcategoria is null) LIMIT $paginadoInicial,15";
    }else{
        $consulta = "SELECT * FROM
                    (
                    SELECT count(id_prod) c FROM producto
                    WHERE nomb_prod LIKE '%$busqueda%' AND precio-descuento*precio/100 BETWEEN $precioInicial and $precioFinal AND categoria LIKE '%$cat%' AND genero LIKE '%$genero%' AND marca LIKE '%$marca%' AND subcategoria LIKE '%$disciplina%') a, producto
                    WHERE nomb_prod LIKE '%$busqueda%' AND precio-descuento*precio/100 BETWEEN $precioInicial and $precioFinal AND categoria LIKE '%$cat%' AND genero LIKE '%$genero%' AND marca LIKE '%$marca%' AND subcategoria LIKE '%$disciplina%' LIMIT $paginadoInicial,15";
    }
    try{
        $allProd = mysqli_query($connDB, $consulta);
    }
    catch(Exception $e){
        return "ERROR";
    }
    $arrayProds = array();
    while($fila = mysqli_fetch_array($allProd)){
        $arrayProds[] = $fila;
    }
    return $arrayProds;   
}

function buscar($busqueda){
    global $connDB;
    $busqueda = mysqli_real_escape_string($connDB, $busqueda);
    $consulta = "SELECT * FROM producto WHERE nomb_prod LIKE '%$busqueda%'";
    try{
        $allProd = mysqli_query($connDB, $consulta);
    }
    catch(Exception $e){
        return "ERROR";
    }
    $arrayProds = array();
    while($fila = mysqli_fetch_array($allProd)){
        $arrayProds[] = $fila;
    }
    return $arrayProds;
    
}

function alertas($connDB){
    $prodsAlerta = array();
    try{
        $allProd = mysqli_query($connDB, "SELECT * FROM producto WHERE stock<=min_stock");
    }
    catch(Exception $e){
        return "ERROR";
    }
    while($fila = mysqli_fetch_array($allProd)){
        $prodsAlerta[] = $fila;
    }
    return $prodsAlerta;
}

function consulta_prod_visibilidad($connDB, $id){
    try{
        $prodVisibilidad = mysqli_query($connDB, "SELECT público FROM producto WHERE id_prod=$id");
    }
    catch(Exception $e){
        return "ERROR";
    }
    $visibilidadRow = array();
    while($fila = mysqli_fetch_array($prodVisibilidad)){
        $visibilidadRow[]=$fila;
    }
    return $visibilidadRow;
}

function realizar_compra_unica($connDB, $id, $usuario){
    //Verifico que el usuario que va a comprar no esté suspendido.
    if(comprobar_susp($connDB, $usuario)!=0){
        return;
    }
    $date = date('Y-m-d');
    $insercion = "INSERT INTO compra VALUES(null, '$date', ( 
        (SELECT precio FROM producto WHERE id_prod=$id) - ( (SELECT precio FROM producto WHERE id_prod=$id)*(SELECT descuento FROM producto WHERE id_prod=$id) / 100)
        ), '$usuario')";
    $stockConsulta = "SELECT stock FROM producto WHERE id_prod=$id";

    try{
        $sqlComprados = mysqli_query($connDB, "SELECT comprados FROM producto WHERE id_prod=$id");
        $sqlStock = mysqli_query($connDB, "SELECT stock FROM producto WHERE id_prod=$id");
        $sqlPublico = mysqli_query($connDB, "SELECT público FROM producto WHERE id_prod=$id");
    }
    catch(Exception $e){
        return "ERROR";
    }

    //-------
    $arrayComprados = array();
    while($fila = mysqli_fetch_array($sqlComprados)){
        $arrayComprados = $fila;
    }
    $comprados = $arrayComprados[0] + 1;
    //-------
    $arrayStock = array();
    while($fila = mysqli_fetch_array($sqlStock)){
        $arrayStock = $fila;
    }
    //-------
    $arrayPublico = array();
    while($fila = mysqli_fetch_array($sqlPublico)){
        $arrayPublico = $fila;
    }
    if($arrayStock[0] == 0 || $arrayStock[0] == "0" || $arrayPublico[0]==0 || $arrayPublico[0]=="0"){
        header("location: mensajes.php?compra_no_realizada");
        return;
    }
    $stockActual = $arrayStock[0];
    $stock = $arrayStock[0] - 1;
    $insercionCompradosStock = "UPDATE producto SET comprados='$comprados', stock='$stock' WHERE id_prod=$id AND público=1";

    try{
        if($stockActual > 0){
            if($connDB->query($insercion) === TRUE && $connDB->query($insercionCompradosStock) === true){
                $insercion = "INSERT INTO tiene VALUES((SELECT MAX(id_compra) FROM compra WHERE nomb_usu='$usuario'), '$id', 1)";
                if($connDB->query($insercion) === TRUE){
                    header("location: mensajes.php?compra_realizada");
                }else{
                    header("location: mensajes.php?compra_no_realizada");
                }
            }else{
                header("location: mensajes.php?compra_no_realizada");
            }
        }
        else{
            header("location: mensajes.php?compra_no_realizada");
        }
    }
    catch(Exception $e){
        echo "ERROR CON LA BASE DE DATOS";
    }
}

function info_user($connDB, $user){
    try{
        $allUsuario = mysqli_query($connDB, "SELECT * FROM usuario WHERE nomb_usu='$user'");
    }
    catch(Exception $e){
        return "ERROR";
    }
    $arrayDatosUser = array();

    while($fila = mysqli_fetch_array($allUsuario)){
        $arrayDatosUser[] = $fila;
    }
    return $arrayDatosUser;
}

function info_persona($connDB, $ci){
    try{
        $allPersona = mysqli_query($connDB, "SELECT * FROM persona WHERE ci='$ci'");
    }
    catch(Exception $e){
        return "ERROR";
    }
    $arrayDatosPer = array();
    while($fila = mysqli_fetch_array($allPersona)){
        $arrayDatosPer[] = $fila;
    }
    return $arrayDatosPer;
}

function info_tel($connDB, $ci){
    try{
        $allTel = mysqli_query($connDB, "SELECT num FROM tel WHERE ci='$ci'");
    }
    catch(Exception $e){
        return "ERROR";
    }
    $arrayDatosTel = array();

    while($fila = mysqli_fetch_array($allTel)){
        $arrayDatosTel[] = $fila;
    }

    return $arrayDatosTel;
}

function consultaPass($connDB, $user){
    try{
        $passUser = mysqli_query($connDB, "SELECT pass FROM usuario WHERE nomb_usu='$user'");
    }
    catch(Exception $e){
        return "ERROR";
    }
    $arrayPass = array();

    while($fila = mysqli_fetch_array($passUser)){
        $arrayPass[] = $fila;
    }
    return $arrayPass[0]['pass'];
}

function getCart($connDB, $user){

    try{
        $prodsCart = mysqli_query($connDB, "SELECT * FROM v_cart WHERE nomb_usu = '$user'");
    }
    catch(Exception $e){
        return "ERROR";
    }
    $arrayCart = array();
    
    while($fila = mysqli_fetch_array($prodsCart)){
        $arrayCart[] = $fila;
    }
    return $arrayCart;
}

function displayGestiones($connDB, $filtro, $busqueda1, $busqueda2, $stockMod, $publicarCheckbox, $ocultarCheckbox){
    try{
        $busqueda2 = str_replace('/','-',$busqueda2);
        $busqueda1 = mysqli_real_escape_string($connDB, $busqueda1);
        $busqueda2 = mysqli_real_escape_string($connDB, $busqueda2);
        $filtro = mysqli_real_escape_string($connDB, $filtro);
        $stockMod = mysqli_real_escape_string($connDB, $stockMod);
        $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones");
    }
    catch(Exception $e){
        return "ERROR";
    }
    if($filtro != ""){
        switch($filtro){
            case '1':
                $busqueda1 = trim($busqueda1);
                if($busqueda1 != ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE nomb_usu = '$busqueda1'");
                }
                break;
            case '2':
                if($stockMod != "" && $publicarCheckbox != "" && $ocultarCheckbox != ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones");
                }else if($stockMod != "" && $publicarCheckbox != "" && $ocultarCheckbox == ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE accion != 'Ocultó'");
                }else if($stockMod == "" && $publicarCheckbox != "" && $ocultarCheckbox != ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE accion != 'Agregó stock' AND accion != 'Quitó stock'");
                }else if($stockMod != "" && $publicarCheckbox == "" && $ocultarCheckbox != ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE accion != 'Publicó'");
                }else if($stockMod != "" && $publicarCheckbox == "" && $ocultarCheckbox == ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE accion != 'Publicó' AND accion != 'Ocultó'");
                }else if($stockMod == "" && $publicarCheckbox != "" && $ocultarCheckbox == ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE accion = 'Publicó'");
                }else if($stockMod == "" && $publicarCheckbox == "" && $ocultarCheckbox != ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE accion = 'Ocultó'");
                }else{
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones");
                }
                break;
            case '3':
                if($busqueda1 != "" && $busqueda2 != ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE fecha BETWEEN '$busqueda1' AND '$busqueda2'");
                }else if($busqueda1 != "" && $busqueda2 == ""){
                    $date = date('Y-m-d');
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE fecha BETWEEN '$busqueda1' AND '$date'");
                }else if($busqueda1 == "" && $busqueda2 != ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE fecha BETWEEN '0-0-0' AND '$busqueda2'");
                }else{
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones");
                }
                break;
            case '4':
                $busqueda1 = trim($busqueda1);
                if($busqueda1 != ""){
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones WHERE id_prod = '$busqueda1'");
                }else{
                    $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones");
                }
                break;    
            default:
                $sql = mysqli_query($connDB, "SELECT * FROM v_gestiones");
                break;
        }
    }
    $arrayGestiones = array();
    while($fila = mysqli_fetch_array($sql)){
        $arrayGestiones[] = $fila;
    }
    return $arrayGestiones;
}

function getUser($connDB, $user){
    try{
        $usuario = mysqli_query($connDB, "SELECT * FROM v_usr_per WHERE nomb_usu = '$user'");
        if($usuario = $connDB->query("SELECT * FROM v_usr_per WHERE nomb_usu = '$user'")){
            $arrayUser = array();
            while($fila = mysqli_fetch_array($usuario)){
                $arrayUser[] = $fila;
            }
            return $arrayUser;
        }
    }
    catch(Exception $e){
        return "ERROR";
    }
    
}
function getProd($connDB, $id){
    try{
        $prod = mysqli_query($connDB, "SELECT * FROM producto WHERE id_prod='$id'");
    }
    catch(Exception $e){
        return "ERROR";
    }
    
    $arrayProd = array();
    while($fila = mysqli_fetch_array($prod)){
        $arrayProd[] = $fila;
    }
    return $arrayProd;
}

function loadHistory($connDB, $user, $id){
    //Selecciono el historial del usuario.
    try{
        $sql = mysqli_query($connDB, "SELECT * FROM busqueda WHERE nomb_usu = '$user'");
    }
    catch(Exception $e){
        return "ERROR";
    }
    $prodsBuscados = array();
    while($fila = mysqli_fetch_array($sql)){
        //Cargo el historial en  el array $prodsBuscados.
        $prodsBuscados[] = $fila;
    }

    //Creo una variable para verificar si el producto ya está en la tabla.
    $hasProd = 0;
    //Creo una variable para almacenar la fecha actual.
    $fechaActual = date('Y-m-d');
    foreach($prodsBuscados as $valor){
        //Recorro el array para saber si el producto ya se encuentra en la tabla.
        if($valor['id_prod'] == $id){
            //Si el producto se encuentra en la tabla, actualizo la fecha a la actual.
            //De esta manera el usuario va a saber únicamente cuando fue la ÚLTIMA VEZ que accedió a un producto.
            $update = "UPDATE busqueda SET fecha = '$fechaActual' WHERE nomb_usu = '$user' AND id_prod = '$id'";
            try{
                $connDB->query($update);
            }catch(Exception $e){
            }
            //Indico que el producto fue encontrado en la tabla.
            $hasProd = 1;
            break;
        }
    }
    //Si el producto no fue encontrado en la tabla:
    if(!$hasProd){
        //Creo un nuevo registro con los datos del usuario, el producto y la fecha actual.
        $insercion = "INSERT INTO busqueda VALUES('$user', '$id', '$fechaActual')";
        try{
            $connDB->query($insercion);
        }catch(Exception $e){

        }
    }
}

function getHistory($connDB, $user){
    try{
        $sql = mysqli_query($connDB, "SELECT * FROM v_historial WHERE nomb_usu = '$user' ORDER BY fecha");
    }
    catch(Exception $e){
        return "ERROR";
    }
    $historial = array();
    while($fila = mysqli_fetch_array($sql)){
        $historial[] = $fila;
    }
    return $historial;
}

function removeHistoryElement($connDB, $user, $id){
    $sqlDelete = "DELETE FROM busqueda WHERE nomb_usu='$user' AND id_prod='$id'";
    try{
    mysqli_query($connDB, $sqlDelete);
    return 1;
    }
    catch(Exception $e){
    }
}

function realizar_compra_cart($connDB, $usuario){
    //Verifico si el usuario está suspendido.
    if(comprobar_susp($connDB, $usuario)!=0){
        return;
    }
    //Obtengo los productos del carrito mediante la función getCart definida en conexionbd.php.
    $prods = getCart($connDB, $usuario);
    //Creo una variable para comprobar el estado de la compra.
    $compraStatus = 0;
    if(empty($prods)){
        //Si el array de productos del carrito está vacío
        //Retorno 2 (código de carrito vacío).
        return 2;
    }
    $precioTotal = 0;
    $error = -1;

    //Array que indicará el nuevo valor del atributo comprados
    //En la tabla producto.
    $comprados = array();

    //Array que almacenará la cantidad de unidades de un
    //Producto en el carrito.
    $cantidad = array();

    //Array que almacenará el nuevo stock.
    $stockNuevo = array();
    
    //Array que almacenará el stock antes de comprar los productos.
    $stockActual = array();
    
    //Array que almacenará las veces que fue comprado
    //El producto antes de realizar cabios.
    $compradosActual = array();

    //Variable para almacenar la fecha.
    $date = date('Y-m-d');
    //Variable contador.

    $i = 0;
    foreach($prods as $prod){
        $id = $prod['id_prod'];
        //Seteo el valor de comprados nuevo sumando el valor actual
        //Mas la cantidad que se quiere comprar.
        $comprados[$i] = $prod['comprados'] + $prod['cantidad'];
        //Seteo la cantidad de unidades de un producto en el carrito.
        $cantidad[$i] = $prod['cantidad'];
        //Seteo la cantidad de veces que fue comprado un producto.
        $compradosActual[$i] = $prod['comprados'];
        //Verificación de si el producto está en público.
        $publico[$i] = $prod['público'];

        if($prod['stock'] <= 0 || $prod['stock'] == "0" || $prod['público']==0 || $prod['público']=="0"){
            //Si el stock actual es menor o igual a 0 seteo $compraStatus a 3
            //(código para falta de stock).
            $compraStatus = 3;
        }
        if($prod['stock'] - $prod['cantidad'] < 0){
            //Si el stock actual menos la cantidad que
            //Se quiere comprar resulta menor que 0,
            //Seteo $compraStatus a 3 (código para falta de stock).
            $compraStatus = 3;
        }
        //Seteo el stock actual.
        $stockActual[$i] = $prod['stock'];
        //Seteo el nuevo stock restando el
        //Stock actual menos la cantidad que se quiere comprar.
        $stockNuevo[$i] = $prod['stock'] - $prod['cantidad'];
        $precioTotalUnitario = $prod['precio']*$prod['cantidad'];
        $precioTotal = $precioTotal + $precioTotalUnitario - $precioTotalUnitario*$prod['descuento']/100;
        if($compraStatus != 3){
            //Si $compraStatus es distinto de 3 (no hay problemas con el stock):
            //Actualizo el campo comprados y el campo stock del producto en cuestión.
            $insercionCompradosStock = "UPDATE producto SET comprados='$comprados[$i]', stock='$stockNuevo[$i]' WHERE id_prod='$id'";
            if($connDB->query($insercionCompradosStock) !== TRUE){
                //Si ocurre un error con la actualización seteo
                //$error en $i para saber en qué ínidice del 
                //Array de productos ocurrió el error.
                $error = $i;
                break;
            }
        }else{
            //Si $compraStatus es igual a 3, seteo
            //$error a $i para saber en qué índice del
            //Array de productos ocurrió el error.
            $error = $i;
        }
        //Sumo uno al contador.
        $i++;
    }
    //Si $error es igual a -1, es decir, si no se 
    //detectaron errores dentro del foreach anterior:
    if($error == -1){
        //Inserto un nuevo registro en la tabla compra.
        $insercion = "INSERT INTO compra VALUES (null, '$date', '$precioTotal', '$usuario')";
        if($connDB->query($insercion)!==TRUE){
            //Si ocurre un problema con la inserción de datos
            //Seteo $errorCompra a 1 (es una variable para saber que ocurrió
            //Un error al cargar el registro de compra).
            $errorCompra = 1;
        }else{
            //Si se registra la compra con éxito:
            $errorCompra = 0;

            //Uso un do while para insertar datos, de
            //Todos los productos involucrados, en la tabla tiene.
            $j=0;
            //$errorTiene es una variable que identifica errores al cargar datos en la tabla tiene.
            $errorTiene = -1;
            do{
                $insercionTiene = "INSERT INTO tiene VALUES((SELECT MAX(id_compra) FROM compra WHERE nomb_usu='$usuario'), '{$prods[$j]['id_prod']}', '{$prods[$j]['cantidad']}')";
                if($connDB->query($insercionTiene)!==TRUE){
                    //Si ocurre algún error al registrar datos de los productos,
                    //Se setea $compraStatus a 0 (código de compra fallida).
                    //Y se setea $errorTiene a 1.
                    $compraStatus = 0;
                    $errorTiene = 1;
                }
                $j++;
            }while($j < $i);
        }
    }else{
        //Si se detectaron errores en el primer foreach:
        //Uso un for para devolver los datos de los productos
        //A como estaban antes de realizar cambios.
        for($k=0;$k<$error;$k++){
            $updateCompradosStock = "UPDATE producto SET comprados=$compradosActual[$k], stock=$stockActual[$k] WHERE id_prod=".$prods[$k]['id_prod'];
            if($connDB->query($updateCompradosStock) === TRUE){
                //Si los datos se actualizan correctamente,
                //Seteo $compraStatus a 0 para indicar que la compra no se realizó.
                $compraStatus = 0;
            }
        }
        $compraStatus = 0;
    }
    if(!isset($errorCompra) || $errorCompra == 1){
        //Si hubo error al cargar el registro de compra:
        //Uso un for para devolver los datos de los productos
        //A como estaban antes de realizar cambios.
        for($k=0;$k<$error;$k++){
            $updateCompradosStock = "UPDATE producto SET comprados=$compradosActual[$k], stock=$stockActual[$k] WHERE id_prod=".$prods[$k]['id_prod'];
            if($connDB->query($updateCompradosStock) === TRUE){
                //Si los datos se actualizan correctamente,
                //Seteo $compraStatus a 0 para indicar que la compra no se realizó.
                $compraStatus = 0;
            }
        }
    }else if(!isset($errorTiene) || $errorTiene == 1){
        //Si no hubo error en el registro de compra pero si en alguno de tiene
        //Borro los registros de compra y tiene que se hayan cargado.
        $delTiene = "DELETE FROM tiene WHERE id_compra=(SELECT MAX(id_compra) FROM compra WHERE nomb_usu='$usuario')";
        $delCompra = "DELETE FROM compra WHERE id_compra=(SELECT MAX(id_compra) FROM compra WHERE nomb_usu='$usuario')";
        if($connDB->query($delTiene)===TRUE){
            if($connDB->query($delCompra)===TRUE){
                $compraStatus = 0;
            }
        }
    }else{
        $compraStatus = 1;
    }
    return $compraStatus;
}
//----------------------------------------------------------

function carrito_calcular_precio($connDB, $usuario){
    //Función getCart definida más arriba en el código.
    $prods = getCart($connDB, $usuario);
    $precioFinal = 0;
    foreach($prods as $prod){
        $precioTotalUnitario = $prod['precio']*$prod['cantidad'];
        $precioFinal = $precioFinal + $precioTotalUnitario - $precioTotalUnitario*$prod['descuento']/100;
    }
    return $precioFinal;
}

function verificar_comprador($connDB, $idCompra){
    $idCompra = mysqli_real_escape_string($connDB, $idCompra);
    $sql = "SELECT nomb_usu FROM compra WHERE id_compra=$idCompra";
    try{
        // 
        if($consulta = $connDB->query($sql)){
            $arrayComprador = array();
        while($fila = mysqli_fetch_array($consulta)){
            $arrayComprador[] = $fila;
        }
        return $arrayComprador[0];
        }
        else{return "ERROR";}
        
    }
    catch(Exception $e){
        return "ERROR";
    }
    
}
?>