<?php
    try{
    include "../php/loginV3/conexionbd.php";
}
catch(Exception $e){
    echo "Error de conexión con la base de datos";
    return;
}
    session_start();
    //Si el botón de cerrar sesión fue presionado, se borran los datos de $_SESSION y redirecciona a login.
    if(isset($_GET['logout'])){
        session_unset();
        header("location:login.php");
    }
    //Si el usuario está suspendido
    if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
        //Se cierra sesión y se redirecciona a login.
        session_unset();
        header("location:login.php");
    }
    if(empty($_SESSION['logUsuario']) || $_SESSION['logTipo']!="empleado"){
        session_unset();
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/panel_emp-styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="icon" href="../iconos/Captura.PNG">
    <title>Panel Empleado - Indumentarias del Plata</title>
</head>
<body>
<?php
        //Si no hay ningún usuario logueado o éste es de un tipo diferente a empleado:
        if($_SESSION['logUsuario'] == null || $_SESSION['logTipo'] != 'empleado'){
            //Se cierra sesión y se redirecciona a login.
            session_unset();
            header("location:login.php");
        }
        echo '<i class="fa-solid fa-bars ham-btn"></i>';
        echo '<header id="navbar">
                <a href="#" id="logo"><img src="../iconos/Captura.PNG" alt=""></a>
                    
                <div id="text-header-container">
                    Panel Empleado
                </div>
                    
                <div id="user-main-container">
                    <i id="bell-btn" class="fa-solid fa-bell"><div></div></i>
                    <ul id="menu-notificaciones">
                    </ul>';
                    ?>
                    <script>
                        //FUNCIÓN PARA DESPLEGAR MENÚ DE NOTIFICACIONES
                        let bell_btn = document.getElementById("bell-btn");
                        bell_btn.addEventListener("click", ()=>{
                            document.getElementById("menu-notificaciones").classList.toggle("notificaciones-active");
                        });
                        //FUNCIÓN PARA SÍMBOLO DE NOTIFICACIÓN (notificaciones sin ver)
                        function displayUnseenAlert(){
                            if(document.getElementById("menu-notificaciones").children.length != 0){
                                if(document.getElementById("menu-notificaciones").children[0].innerHTML == "<p>No tienes notificaciones.</p>"){
                                    bell_btn.children[0].style.display='none';
                                }else{
                                    bell_btn.children[0].style.display='block';
                                }
                            }else{
                                bell_btn.children[0].style.display='none';
                            }
                        }

                        function checkAlerts(){
                            $.ajax({
                                url:"../php/loginV3/validarProds.php",
                                method: "post",
                                data:{
                                    prodAlerts: "TRUE"
                                },
                                success: function(response){
                                    document.getElementById("menu-notificaciones").innerHTML = response;
                                    displayUnseenAlert();
                                }
                            });
                        }
                        checkAlerts();
                    </script>
                <?php
                    echo '</ul>
                    <div id="user-container" onclick="deslizar_info()">';
                        echo $_SESSION['logUsuario'].'<i id="user-icon" class="fa-solid fa-user"></i>
                    </div>
                </div>
                    
            </header>';
            echo '<form id="user-info" action="panel_emp.php?logout" method="post">
                    <div id="account"><a href="mi_cuenta.php">Mi Cuenta</a></div>
                    <input type="submit" id="logout" readonly="readonly" value="Cerrar sesión" name="logout">
                </form>';
                
                    //Si el botón de cerrar sesión fue presionado, se borran los datos de $_SESSION y redirecciona a login.
                if(isset($_GET['logout'])){
                    session_unset();
                    header("location:login.php");
                }
        ?>

    <div id="panel">
        <div id="reponer-stock" class="panel-items">Gestión de productos</div>
        <div id="ver-gestiones" class="panel-items">Ver gestiones</div>
    </div>

    <div id='function-container'>
        
        <div id='reponer-stock-container' class='function-containers function-in'>

            <form id='search-bar'>
                <input id='barra-busqueda' type="text" name='buscar' placeholder='Buscar...'>
                <input id='buscar-submit' type="button" value='Buscar'>
                <input type="button" value='Refrescar' id='refresh'>
            </form>

        <?php
            $allProd = mysqli_query($connDB, "SELECT * FROM producto");
            $arrayProds = array();
            while($fila = mysqli_fetch_array($allProd)){
                $arrayProds[] = $fila;
            }
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
                            <input id='add-stock$id' type='button' value='Agregar stock' class='botonesStock'>
                            <button type='button' id='eye$id' class='visibility-button'><i id='eye-i$id' class='fa-regular fa-eye'></i></button>
                            <p id='mensajesStock$id' class='mensajesStock'> &nbsp </p>
                        </div>
                   </form>";
        }
        echo "<div id='change-page-container'><button id='change-page-previous'>Anterior</button><button id='change-page-next'>Siguiente</button></div>";
    ?>
    
        </div>

        
        <!-- Ver gestiones -------------------------------------------------------------------------------------- -->            
        <div id='gestiones-container' class='function-containers'>
            <?php
                if(isset($_GET['gestion'])){
                    echo "<script>
                        document.getElementById('reponer-stock-container').classList.remove('function-in');
                        document.getElementById('gestiones-container').classList.add('function-in');
                        </script>";
                }
            ?>
                <form action='panel_emp.php?gestion' id='col-select-container'>
                    <label>Código <input class='checks-col' type="checkbox" name="col-gestion" id="cod"></label>
                    <label>Usuario <input class='checks-col' type="checkbox" name="col-nomb" id="usu"></label>
                    <label>Acción <input class='checks-col' type="checkbox" name="col-accion" id="acc"></label>
                    <label>Información <input class='checks-col' type="checkbox" name="col-info" id="info"></label>
                    <label>Fecha <input class='checks-col' type="checkbox" name="col-fecha" id="fecha"></label>
                    <label>ID Producto <input class='checks-col' type="checkbox" name="col-id_prod" id="id_prod"></label>
                    <label>Producto <input class='checks-col' type="checkbox" name="col-prod" id="prod"></label>
                    <input type="button" value='Aplicar' id='col-select-btn'>
            </form>

                <form id='filtro-gestion' method='post' action='panel_emp.php?gestion'>
                    <label for="">
                        Filtrar por: 
                        <select name="filtros" id="gestiones-select">
                            <option value="1" selected>Usuario</option>
                            <option value="2">Acción</option>
                            <option value="3">Fecha</option>
                            <option value="4">Producto (ID)</option>
                        </select>
                    </label>
                    <input type="text" name='busqueda1' id='busqueda-gestion'>
                    <input type="date" name='busqueda2' id='busqueda-gestion-fecha'>
                    <label class="checkbox-labels" for="">Modificación stock: <input type="checkbox" name="stock-mod" value='1' id="checkbox-stock"></label>
                    <label class="checkbox-labels" for="">Publicó: <input type="checkbox" name="publicar-checkbox" value='2' id="checkbox-publicar"></label>
                    <label class="checkbox-labels" for="">Ocultó: <input type="checkbox" name="ocultar-checkbox" value='3' id="checkbox-ocultar"></label>
                    <input type='submit' value='Aplicar'>
                    
                    <!-- Botón para limpiar, manda otro formulario. -->
                    <input type="submit" form='clear-filters' value="Limpiar">
                </form>
                <form id='clear-filters' action="panel_emp.php?gestion" method='POST'>
                    <input type="hidden" name='filtros' value='' selected>
                    <input type="hidden" name='busqueda1' value=''>
                    <input type="hidden" name='busqueda2' value=''>
                    <input type="hidden" name='stock-mod' value=''>
                    <input type="hidden" name='publicar-checkbox' value=''>
                    <input type="hidden" name='ocultar-checkbox' value=''>
                </form>
                <section id='gestiones'>
                    <?php
                    if(isset($_POST['filtros'])){
                        if(isset($_POST['busqueda1']) || isset($_POST['busqueda2']) || isset($_POST['stock-mod']) || $_POST['publicar-checkbox'] || isset($_POST['ocultar-checkbox'])){
                            switch($_POST['filtros']){
                                case '1':
                                    if(isset($_POST['busqueda1'])){
                                    $gestiones = displayGestiones($connDB, $_POST['filtros'], $_POST['busqueda1'], "", "", "", "");
                                }
                                break;
                                case '2';
                                    if( 
                                        (isset($_POST['stock-mod']) && $_POST['stock-mod'] == '1') &&
                                        (isset($_POST['publicar-checkbox']) && $_POST['publicar-checkbox'] == '2') &&
                                        (isset($_POST['ocultar-checkbox']) && $_POST['ocultar-checkbox'] == '3')
                                        ){
                                        $gestiones = displayGestiones($connDB, $_POST['filtros'], "", "", $_POST['stock-mod'], $_POST['publicar-checkbox'], $_POST['ocultar-checkbox']);
                                    }else if(
                                            (isset($_POST['stock-mod']) && $_POST['stock-mod'] == '1') &&
                                            (isset($_POST['publicar-checkbox']) && $_POST['publicar-checkbox'] == '2') &&
                                            (!isset($_POST['ocultar-checkbox']) || $_POST['ocultar-checkbox'] != '3')
                                            ){
                                            $gestiones = displayGestiones($connDB, $_POST['filtros'], "", "", $_POST['stock-mod'], $_POST['publicar-checkbox'], "");
                                    }else if(
                                            (!isset($_POST['stock-mod']) || $_POST['stock-mod'] != '1' ) &&
                                            (isset($_POST['publicar-checkbox']) && $_POST['publicar-checkbox'] == '2') &&
                                            (isset($_POST['ocultar-checkbox']) && $_POST['ocultar-checkbox'] == '3')
                                            ){
                                            $gestiones = displayGestiones($connDB, $_POST['filtros'], "", "", "", $_POST['publicar-checkbox'], $_POST['ocultar-checkbox']);
                                    }else if(
                                            (isset($_POST['stock-mod']) && $_POST['stock-mod'] == '1') &&
                                            (!isset($_POST['publicar-checkbox']) || $_POST['publicar-checkbox'] != '2' ) &&
                                            (isset($_POST['ocultar-checkbox']) && $_POST['ocultar-checkbox'] == '3')
                                            ){
                                            $gestiones = displayGestiones($connDB, $_POST['filtros'], "", "", $_POST['stock-mod'], "", $_POST['ocultar-checkbox']);
                                    }else if(
                                            (isset($_POST['stock-mod']) && $_POST['stock-mod'] == '1') &&
                                            (!isset($_POST['publicar-checkbox']) || $_POST['publicar-checkbox'] != '2' ) &&
                                            (!isset($_POST['ocultar-checkbox']) || $_POST['ocultar-checkbox'] != '3')
                                            ){
                                            $gestiones = displayGestiones($connDB, $_POST['filtros'], "", "", $_POST['stock-mod'], "", "");
                                            }else if(
                                                (!isset($_POST['stock-mod']) || $_POST['stock-mod'] != '1') &&
                                                (isset($_POST['publicar-checkbox']) && $_POST['publicar-checkbox'] == '2' ) &&
                                                (!isset($_POST['ocultar-checkbox']) || $_POST['ocultar-checkbox'] != '3')
                                            ){
                                            $gestiones = displayGestiones($connDB, $_POST['filtros'], "", "", "", $_POST['publicar-checkbox'], "");
                                            }else if(
                                                (!isset($_POST['stock-mod']) || $_POST['stock-mod'] != '1') &&
                                                (!isset($_POST['publicar-checkbox']) || $_POST['publicar-checkbox'] != '2' ) &&
                                                (isset($_POST['ocultar-checkbox']) && $_POST['ocultar-checkbox'] == '3')){
                                            $gestiones = displayGestiones($connDB, $_POST['filtros'], "", "", "", "", $_POST['ocultar-checkbox']);
                                            }else{
                                                $gestiones = displayGestiones($connDB, "", "", "", "", "", "");
                                            }
                                break;
                                case '3':
                                    if(isset($_POST['busqueda1']) && isset($_POST['busqueda2'])){
                                        $gestiones = displayGestiones($connDB, $_POST['filtros'], $_POST['busqueda1'], $_POST['busqueda2'], "", "", "");
                                    }else if(isset($_POST['busqueda1']) && !isset($_POST['busqueda2'])){
                                        $gestiones = displayGestiones($connDB, $_POST['filtros'], $_POST['busqueda1'], "", "", "", "");
                                    }else if(!isset($_POST['busqueda1']) && isset($_POST['busqueda2'])){
                                        $gestiones = displayGestiones($connDB, $_POST['filtros'], "", $_POST['busqueda2'], "", "", "");
                                    }
                                break;
                            case '4':
                                if(isset($_POST['busqueda1'])){
                                    $gestiones = displayGestiones($connDB, $_POST['filtros'], $_POST['busqueda1'], "", "", "", "");
                                }
                                break;
                                default:
                                    $gestiones = displayGestiones($connDB, "", "", "", "", "", "");
                                break;
                            }
                        }
                    }else{
                        $gestiones = displayGestiones($connDB, "", "", "", "", "", "");
                    }
                        if(empty($gestiones)){
                            echo "<h1>Aún no se han realizado gestiones.</h1>";
                        }else{
                            echo "<table id='gestiones-table'>
                                    <tr id='gestiones-cabecera'>
                                        <th class='col-gestion'>Código</th>
                                        <th class='col-nomb'>Usuario</th>
                                        <th class='col-accion'>Acción</th>
                                        <th class='col-info'>Información</th>
                                        <th class='col-fecha'>Fecha</th>
                                        <th class='col-id_prod'>ID Producto</th>
                                        <th class='col-prod''>Producto</th>
                                    </tr>";
                                foreach($gestiones as $value){
                                    echo "<tr class='gestion-row'>
                                            <td class='col-gestion'>$value[id_gestion]</td>
                                            <td class='col-nomb'>$value[nomb_usu]</td>
                                            <td class='col-accion'>$value[accion]</td>";
                                        if($value['info'] == null){
                                            echo "<td class='col-info'>-</td>";
                                        }else{
                                            echo "<td class='col-info'>$value[info]</td>";
                                        }
                                    echo "<td class='col-fecha'>$value[fecha]</td>
                                            <td class='col-id_prod'>$value[id_prod]</td>
                                            <td class='col-prod'>$value[nomb_prod]</td>
                                        </tr>";
                                    }
                            echo "</table>";
                        }
                    ?>
                </section>
        </div>
    </div>
    <script>
        //Cuando el documento esté cargado se crea y llama una variable anónima.
        $(document).ready(function(){
            //Cuando se le da clic a un botón de la clase botonesStock, se llama a la función stock() parámetro event.
            function stock(event){
                //Variable extraerId: /(\d+)/g significa que extraerá todos los números.
                let extraerId = /(\d+)/g;
                //Variable idTarget, guardará la id del botón clickeado.
                let idTarget = event.target.id;
                //Ahora idTarget solo será la parte numérica de cada botón. Ejemplo: add-stock45, idTarget será 45.
                idTarget = idTarget.match(extraerId).toString();
                //Variable nuevo, guardará el valor del nuevo stock ingresado por el usuario en el input de número idTarget.
                let nuevo = $(`#newStockInput${idTarget}`).val();
                //Variable actual, guardará el valor del input stockActualInput en el número idTarget.
                let actual = $(`#stockActualInput${idTarget}`).val();
                let actionType;
                //2 Checkbox, cb1 es para agregar stock, y cb2 el de sobreescribir.
                let cb1 = document.querySelector(`#stockAdd${idTarget}`);
                let cb2 = document.querySelector(`#stockOverwrite${idTarget}`);
                //Si el escogido es cb1, el tipo de acción será "add", y si es el cb2, será "overwrite".
                if(cb1.checked){
                    actionType = "add";
                }
                else if(cb2.checked){
                    actionType = "overwrite";
                }
                //Si el nuevo stock ingresado es mayor 0 igual a 0 y distinto de un string vacío:
                if(nuevo!="" && nuevo>=0){
                    //Si está checkeado cb2(sobreescribir), el nuevo stock no puede ser igual al actual, y si está cb1 checkeado, esto no será necesario.
                    if(cb2.checked && nuevo!=actual || cb1.checked){

                        //Si actionType es distinto de null y de string vacío, se enviará un ajax.
                        if(actionType!=null && actionType!=""){
                            $.ajax({
                                //Ajax hará referencia al url '../php/loginV3/validarProds.php'
                                url:'../php/loginV3/validarProds.php',
                                //Por el método POST
                                method: 'POST',
                                //Los datos enviados serán en un objeto.
                                data: {
                                    reponerStock: "TRUE",
                                    idProd: idTarget,
                                    newStock: nuevo,
                                    actionType: actionType
                                },
                                //En caso de recibir respuesta.
                                success: function(response){
                                    let respuesta = JSON.parse(response);
                                    //Se crea una variable a la que se asignará la respuesta en formato JSON.
                                    let mensaje;
                                    if(respuesta.status==1){
                                        document.getElementById(`mensajesStock${idTarget}`).style.color="white";
                                        setTimeout(() => {
                                            document.getElementById(`mensajesStock${idTarget}`).style.color="green";
                                        }, 200);
                                        document.getElementById(`mensajesStock${idTarget}`).innerHTML="Stock modificado correctamente.";
                                        document.getElementById(`actualStock${idTarget}`).innerHTML="Stock: "+respuesta.stock;
                                        document.getElementById(`stockActualInput${idTarget}`).value=respuesta.stock;
                                    }
                                    else{
                                        document.getElementById(`mensajesStock${idTarget}`).style.color="red";
                                        document.getElementById(`mensajesStock${idTarget}`).innerHTML="Error al modificar stock.";
                                    }
                                }
                            });
                        }
                    }
                    //Si se desea sobreescribir y el stock ingresado es igual al actual aparecerá un mensaje de error en rojo.
                    else if(cb2.checked && nuevo==actual){
                        document.getElementById(`mensajesStock${idTarget}`).style.color="white";
                        setTimeout(() => {
                            document.getElementById(`mensajesStock${idTarget}`).style.color="red";
                        }, 100);
                        document.getElementById(`mensajesStock${idTarget}`).innerHTML="El stock ingresado es igual al actual.";
                    }
                }
                else{
                    document.getElementById(`mensajesStock${idTarget}`).style.color="red";
                    document.getElementById(`mensajesStock${idTarget}`).innerHTML="El stock ingresado no es válido.";
                }
            }
            $(".botonesStock").click(function(){
                stock(event);
            });
            
            function visibilidad(event){
                let extraerId = /(\d+)/g;
                let id = event.target.id;
                id=id.match(extraerId).toString();
                $.ajax({
                    url:'../php/loginV3/validarProds.php',
                    method:'POST',
                    data:{
                        idProdOcultar:id
                    },
                    success:function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status==1){
                            if(respuesta.visibilidad==0){
                                document.getElementById("eye-i"+id).classList.remove("fa-eye");
                                document.getElementById("eye-i"+id).classList.add("fa-eye-slash");
                                document.getElementById("eye"+id).style.color="red";
                            }
                            else if(respuesta.visibilidad==1){
                                document.getElementById("eye-i"+id).classList.remove("fa-eye-slash");
                                document.getElementById("eye-i"+id).classList.add("fa-eye");
                                document.getElementById("eye"+id).style.color="#46b9d6";
                            }
                        }
                        
                    }
                });
            }
            $("#barra-busqueda").on("keypress", (event)=>{
                if(event.key=="Enter"){
                    event.preventDefault();
                    document.getElementById("buscar-submit").click();
                }
            });
            let busqueda = "", pag = 1;
            function refresh(){
                $.ajax({
                    url:'../php/loginV3/validarFiltros.php',
                    method:"POST",
                    data: {
                        refreshEMP: "TRUE",
                        pag: pag,
                        busqueda: busqueda
                    },
                    success:function(response){
                        document.getElementById("reponer-stock-container").innerHTML="<form id='search-bar' action=''><input type='text' id='barra-busqueda' name='buscar' placeholder='Buscar...'><input type='button' id='buscar-submit' value='Buscar'><input type='button' value='Refrescar' id='refresh'></form>"+response+"<div id='change-page-container'><button id='change-page-previous'>Anterior</button><button id='change-page-next'>Siguiente</button></div>";
                        
                        $("#buscar-submit").on("click", () => {
                            busqueda = $("#barra-busqueda").val();
                            refresh();    
                        });
                        $(".botonesStock").click(function(){
                            stock(event);
                        });
                        $("#refresh").on("click", function(){
                            refresh();
                        });
                        $(".fa-regular").click(function(){
                            visibilidad(event);
                        });
                        $("#change-page-previous").on("click", ()=>{
                            if(pag > 1){
                                pag--;
                                refresh();
                            }
                        });
                        $("#change-page-next").on("click", ()=>{
                            let cantPags = $("#cantProds").val()/15;
                            if(cantPags%1 != 0){
                                cantPags = parseInt(cantPags) + 1;
                            }
                            if(pag < cantPags){
                                pag++;
                                refresh();
                            }
                        });
                        $("#barra-busqueda").on("keypress", (event)=>{
                            if(event.key=="Enter"){
                                event.preventDefault();
                                document.getElementById("buscar-submit").click();
                            }
                        });
                    }
                });
            }
            $("#refresh").on("click", refresh());
        });
    </script>
    <script src='../scripts/panel-emp-script.js'></script>
    <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>
</body>
</html>