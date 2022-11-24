<?php
try{
    include_once "../php/loginV3/conexionbd.php";
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
    if(empty($_SESSION['logUsuario']) || $_SESSION['logTipo']!="adm"){
        session_unset();
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,user-scalable=no ,initial-scale=1.0">
    <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/panel_adm-styles.css">
    <link rel="icon" href="../iconos/Captura.PNG">
    <title>Panel Administrador - Indumentarias del Plata</title>
</head>
<body>
<?php
    //Si no hay ningún usuario logueado o éste es de un tipo diferente a admin:
        if($_SESSION['logUsuario'] == null || $_SESSION['logTipo'] != 'adm' || isset($_GET['logout'])){
            //Se cierra sesión y se redirecciona a login.
            session_unset();
            header("location:login.php");
        }
        echo '<i class="fa-solid fa-bars ham-btn"></i>
            <header id="navbar">
                <a href="panel_adm.php" id="logo"><img src="../iconos/Captura.PNG" alt=""></a>
                <div id="text-header-container">
                    Panel Administrador
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
                                data:
                                {
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
        echo '<form id="user-info" action="panel_adm.php?logout" method="post">
                <div id="account"><a href="mi_cuenta.php">Mi Cuenta</a></div>
                    <input type="submit" id="logout" readonly="readonly" value="Cerrar sesión" name="logout">
            </form>';
?>
    <div id="panel">
        <div id="user-add" class="panel-items">Agregar usuario</div>
        <div id="user-mod" class="panel-items">Modificar usuario</div>
        <div id="user-del" class="panel-items">Suspender usuario</div>
        <div id="prod-add" class="panel-items">Agregar producto</div>
        <div id="prod-mod" class="panel-items">Modificar producto</div>
        <div id="ver-prods" class="panel-items">Ver catálogo</div>
        <div id="ver-gestiones" class="panel-items">Ver gestiones</div>
    </div>
    <div id="function-container">
        <!-- Agregar usuario ----------------------------------------------------------------------------------- -->
        <form action="../php/loginV3/validar.php?register" method="post" id="user-add-container" class="function-containers function-in">
            <h2>Agregar usuario</h2>
            <div><b>Nombre de usuario:</b> <input class="info-inputs" type="text" id="username" name="registerUsername"></div>
            <div><b>Contraseña:</b> <input class="info-inputs" type="password" id="passwd" name="registerPassword"></div>
            <div><b>Contraseña:</b> <input class="info-inputs" type="password" id="passwd2" name="registerPassword2"></div>
            <div><b>Correo electrónico:</b> <input class="info-inputs" type="text" id="email" name="registerEmail"></div>
            <div>
                <b>Tipo: </b>
                <select name='registerTipo' id='tipo' class='info-inputs'>
                    <option value="0">Cliente</option>
                    <option value="1">Empleado</option>
                    <option value="2">Administrador</option>
                </select>
            </div>
            <div><b>Nombre:</b> <input class="info-inputs" type="text" id="nombre" name="registerNom"></div>
            <div><b>Apellido:</b> <input class="info-inputs" type="text" id="apellido" name="registerApe"></div>
            <div><b>Cédula de identidad:</b> <input class="info-inputs" type="text" id="ci" name="registerCi"></div>
            <div><b>Número de teléfono:</b> <input class="info-inputs" type="tel" id="tel" name="registerTel"></div>
            <div><b>Fecha de nacimiento:</b> <input class="info-inputs" type="date" id="fechaNac" name="registerFecha"></div>
            <input type='button' id='registrar' value='Registrar' name='confirmar'>
            <p id="mensajesRegistro">&nbsp</p>
        </form>

        <!-- Modificar usuario ------------------------------------------------------------------------------------- -->
        <form action="" method="" id="user-mod-container" class="function-containers">
            <h2>Modificar usuario</h2>
           <!-- Usuario a modificar -->
           <div><b>Nombre de usuario a modificar:</b> <input type="text" name='usuarioModificar' id='usuarioModificar' class='user-mod-inputs'><button id='buscarUser'>Buscar</button></div>
            <!-- Cambiar número de teléfono -->
            <div><b>Número de teléfono:</b> <input type="number" name='telNum' id='telNum' class='user-mod-inputs'></div>
            <!-- Cambiar tipo de usuario -->
            <div>
                <b>Tipo: </b>
                <select name='newUserType' id='selectTipo' class='user-mod-inputs'>
                    <option value="0">Cliente</option>
                    <option value="1">Empleado</option>
                    <option value="2">Administrador</option>
                </select>
            </div>
            <!-- Cambiar correo electrónico -->
            <div><b>Correo:</b> <input type='text' name='newEmail' id='emailMod' class='user-mod-inputs'></div>
            <!-- Cambiar ubicación -->
            <div><b>Ciudad:</b> <input type="text" name='ciudad' onkeydown="return /[a-z]/i.test(event.key)" id='ciudad' class='user-mod-inputs'></div>
            <div><b>Calle 1:</b> <input type="text" name='calle1' id='calle1' class='user-mod-inputs'></div>
            <div><b>Calle 2:</b> <input type="text" name='calle2' id='calle2' class='user-mod-inputs'></div>
            <div><b>Calle 3:</b> <input type="text" name='calle3' id='calle3' class='user-mod-inputs'></div>
            <div><b>Número/descripción:</b> <input type="text" name='nro' id='nro' class='user-mod-inputs'></div> 
            <div><b>Contraseña de la cuenta:</b> <input type='password' name='pass' id='newPassword' class='user-mod-inputs'></div>
            <?php
        ?>
            <input type='button' id='modificarEnviar' value='Enviar'>
            <p id='user-mod-mensajes'>&nbsp</p>
        </form>
        <!-- Suspender/quitar suspensión usuario ------------------------------------------------------------------- -->
        <form action="" method="post" id="user-del-container" class="function-containers">
            <h2>Suspender usuario</h2>
            <div><b>Nombre del usuario: </b><input type="text" id="username-del" placeholder="Nombre del usuario" name="usernameDel" required></div>
            <label><input type="radio" name='suspender' id="suspender1" value='1'>&nbsp Suspender</label>
            <label><input type="radio" name='suspender' id="suspender2" value='0'>&nbsp Quitar suspensión</label>
            <p id="mensajesSusp">&nbsp</p>
            <button id='del-user-btn' type='button' onclick='desplegar_alerta()'>Aplicar</button>
            <div class='del-confirm' id='cartel-user-del'>
                <h2 id='title-user-del'></h2>
                <div>
                    <button type='button' id='cancel-user-del'class='alerta-buttons'>Cancelar</button>
                    <input type="button" id="del-user-submit" value='Aplicar'class='alerta-buttons'>
                </div>
            </div>
        </form>
        <!-- Agregar producto -------------------------------------------------------------------------------------- -->
        <form action="" method="post" id="prod-add-container" class="function-containers">
            <h2>Agregar producto</h2>
            <div><b>Nombre: </b><input type="text" id="prod-name" class="prod-add-inputs" name="nuevoProdNom" required></div>
            <div><b>Precio (U$D): </b><input type="number" id="prod-prec" class="prod-add-inputs" name="nuevoProdPrecio" required></div>
            <div><b>Categoría: </b><input type="text" id="prod-cat" class="prod-add-inputs" name="nuevoProdCat" required></div>
            <div>
                <b>Género: </b>
                <select name="nuevoProdGenero" id="prod-genero" class="prod-add-inputs">
                    <option value="*" selected>Unisex</option>
                    <option value="H">Hombre</option>
                    <option value="M">Mujer</option>
                </select>
            </div>
            <div><b>Subcategoría: </b><input type="text" id="prod-subCat" class="prod-add-inputs" name="nuevoProdSubCat"></div>
            <div><b>Marca: </b><input type="text" id="prod-marca" class="prod-add-inputs" name="nuevoProdMarca" required></div>
            <div>
                <b>Visibilidad: </b>
                <select name="nuevoProdVisibilidad" id="prod-visibilidad" class="prod-add-inputs">
                    <option value="1" selected>Público</option>
                    <option value="0">Oculto</option>
                </select>
            </div>
            <div><b>Stock: </b><input type="number" id="prod-stock" class="prod-add-inputs" name="nuevoProdStock" required></div>
            <div><b>Descripción: &nbsp</b><textarea name="nuevoProdDescripcion" id="prod-descrip" class="prod-add-inputs"></textarea></div>
            <div><b>Descuento: </b><input type="number" id="prod-desc" class="prod-add-inputs" name="nuevoProdDescuento"></div>
            <div>
                <b>Seleccionar imagen: &nbsp</b>
                <input type="file" id="prod-src" name="imagenProd" accept="image/png, image/jpg, image/jpeg, image/jfif" required>
                <label for="prod-src" id="label-src" class="prod-add-inputs"><i class="fa-solid fa-upload"></i></label>
                <input type="hidden" name="nuevoProdImg" id="hidden-input">
            </div>
            <div><b>Stock de seguridad: </b><input type="text" id="prod-minStock" class="prod-add-inputs" name="nuevoProdMinStock"></div>
            <?php
            echo "<input type='hidden' name='user' value='{$_SESSION['logUsuario']}'>";
            ?>
            <input type="button" id='confirmarNuevoProd' value="Agregar">
            <p id="mensajes" class='errores'>&nbsp</p>
        </form>
        <!-- Modificar producto -------------------------------------------------------------------------------------- -->
        <form action="" method="" id="prod-mod-container" class="function-containers">
        <h2>Modificar producto</h2>
           <!-- Producto a modificar -->
           <div><b>Código de producto a modificar: </b><input type="text" name='productoModificar' id='productoModificar' class='prod-mod-inputs'><button type='button' id='buscarProd'>Buscar</button></div>
            <!-- Cambiar nombre -->
            <div><b>Nombre: </b><input type="text" name='nombProd' id='nombProdMod' class='prod-mod-inputs'></div>
      
            <!-- Cambiar precio -->
            <div><b>Precio: </b><input type="text" name='precioProd' id='precioProdMod' class='prod-mod-inputs'></div>

            <!-- Cambiar categoría -->
            <div><b>Categoría: </b><input type='text' name='categoria' id='catProdMod' class='prod-mod-inputs'></div>
            
            <!-- Cambiar descuento -->
            <div><b>Descuento: &nbsp</b><input type="number" name='descuento' id='descuentoProdMod' class='prod-mod-inputs'></div>
            
            <!-- Cambiar descripción -->
            <div><b>Descripción: &nbsp</b><textarea name='descripcion' id='descripcionProdMod' class='prod-mod-inputs'></textarea></div>
            
            <!-- Cambiar imagen -->
            <div>
                <b>Imagen: &nbsp</b>
                <input type="file" id="prod-mod-src" name="imagenProdMod" accept="image/png, image/jpg, image/jpeg, image/jfif">
                <label for="prod-mod-src" id="label-prodMod-src" class="prod-mod-inputs"><i class="fa-solid fa-upload"></i></label>
                <input type="hidden" name="prodModImg" id="hidden-input-prodMod">
            </div>
            
            <!-- Cambiar stock de seguridad -->
            <div><b>Stock de seguridad: </b><input type="number" name='minStock' id='minStockProdMod' class='prod-mod-inputs'></div>
            <?php
        ?>
            <input type='button' id='modificarProdEnviar' value='Enviar'>
            <p id='prod-mod-mensajes'>&nbsp</p>
        </form>

    <script>
$(document).ready(function(){
    $("#del-user-submit").on('click', function(){
        let nombre = $("#username-del").val();
        let suspAction;
        let suspCb1 = document.querySelector("#suspender1");
        let suspCb2 = document.querySelector("#suspender2");
        if(suspCb1.checked){
            suspAction="suspender";
        }
        if(suspCb2.checked){
            suspAction="quitarSusp";
        }
        $.ajax({
            url: '../php/loginV3/validarMiCuenta.php',
            method: 'POST',
            data: {
                suspenderCuenta: "TRUE",
                nombreSusp: nombre,
                suspenderAction: suspAction
            },
            success:function(response){
                let respuesta = JSON.parse(response);
                if(respuesta.vacio==1){
                    document.getElementById("mensajesSusp").style.color="red";
                    document.getElementById("mensajesSusp").innerHTML="Por favor, ingrese un nombre de usuario.";
                }
                if(respuesta.status==1){
                    if(suspCb1.checked){
                        document.getElementById("mensajesSusp").style.color="green";
                        document.getElementById("mensajesSusp").innerHTML="Usuario suspendido.";
                    }
                    if(suspCb2.checked){
                        document.getElementById("mensajesSusp").style.color="green";
                        document.getElementById("mensajesSusp").innerHTML="Suspensión quitada.";
                    }
                }
                else if(respuesta.status==0){
                    document.getElementById("mensajesSusp").style.color="red";
                    document.getElementById("mensajesSusp").innerHTML="Ha ocurrido un error.";
                }
            }
        });
    });
});
//Cuando la página se cargue se ejecutará una función anónima.
$(document).ready(function(){
    //Esa función anónima contiene otra, la cual también es anónima.
    $('#confirmarNuevoProd').on('click', function(){
        let formulario = new FormData($("#prod-add-container")[0]);
        //Función Ajax.
        $.ajax({
            //Ajax hará referencia al url '../php/loginV3/validarProds.php'
            url:'../php/loginV3/validarProds.php',
            //Por el método POST
            method: 'POST',
            processData: false,
            contentType:false,
            //Los datos enviados serán en un objeto.
            data: formulario,
            //En caso de recibir respuesta.
            success: function(response){
                //Se crea una variable a la que se asignará la respuesta en formato JSON.
                let respuesta = JSON.parse(response);
                let mensaje;
                //Se declara la variable stop para usar luego.
                let stop=0;
                //For in de respuesta.
                for (const key in respuesta){
                    if(respuesta.hasOwnProperty(key)){
                        //Si la respuesta en el índice actual es diferente de 0.
                        if(respuesta[key]!=0){
                            //Se asigna 1 a la variable stop y se rompe el bucle.
                            stop = 1;
                            break;
                        }
                    }
                }    
                //Si todo sale bien y stop es igual a 0.
                if(stop==0){
                    //Se cambia el color de mensajes a verde.
                    document.getElementById("mensajes").style.color = "green";
                    //Se coloca en mensajes el mensaje "Producto cargado con éxito".
                    document.getElementById("mensajes").innerHTML = "Producto cargado con éxito";
                    setTimeout(()=>{document.getElementById("mensajes").innerHTML = "&nbsp";},2000);
                    //Se vacían todos los inputs del formulario.
                    for(let input of document.getElementsByClassName('prod-add-inputs')){
                        input.value = "";
                    }

                    document.getElementById("label-src").innerHTML = "<i class='fa-solid fa-upload'></i>";
                    document.getElementById("prod-src").innerHTML = "";
                    document.getElementById("prod-src").value = "";
                    document.getElementById("hidden-input").innerHTML = "";
                    document.getElementById("hidden-input").value = "";
                }
                //Si el valor de la variable stop es diferente de 0
                else{
                    //Se crea "errores" con las mismas claves de respuesta y los mensajes de error.
                    let errores = {
                        "vacio":"Uno o más campos están vacíos.",
                        "prohibidos":"Uno o más campos contienen caracteres prohibidos.",
                        "errorCarga":"Ha ocurrido un error con la base de datos.",
                        'precioNegativo': "El precio no puede ser menor a 1.",
                        'minStockNegativo': "El stock de seguridad debe ser mayor a 0.",
                        'errorVisibilidad': "Por favor ingresar si el producto es público.",
                        'stockNegativo': "El stock no puede ser negativo.",
                        'descuentoInvalido': "El descuento no puede ser mayor a 99 ni menor a 0.",
                        'compradosNegativo': "El producto no puede haber sido comprado menos de 0 veces."
                        };
                    let mensaje;
                    //For in respuesta
                    for (const key in respuesta){
                        if(respuesta.hasOwnProperty(key)){
                            ///Si respuesta en key es 1.
                            if(respuesta[key] == 1){
                                //Se asigna a la variable mensaje el error en la key que sea 1 en respuesta.
                                let mensaje = errores[key];
                                //Se cambia a color rojo "mensajes".
                                document.getElementById("mensajes").style.color = "red";
                                //Se asigna a mensajes el valor "Error: (mensaje de error)".
                                document.getElementById("mensajes").innerHTML = "Error: "+mensaje;
                                setTimeout(()=>{document.getElementById("mensajes").innerHTML = "&nbsp";},2000);
                            }
                        }
                    }
                }
            },
            //En caso de error.
            error: function(){
                //Muestra en un console.error la palabra "error".
                console.error("error");
            },
            dataType:"text"
        });
    });
//Cuando la página está cargada se ejecuta una función anónima.
$(document).ready(function(){
    //La función anónima que se ejecutará al dar clic en registrar tiene otra función llamada "a", 
    //que a su vez llamará a la función reg. 
    $('#registrar').on('click', function a (){reg()});
    //La función reg asigna los valores de los inputs a variables.
    function reg(){
        var username = $('#username').val();
        var password = $('#passwd').val();
        var password2 = $('#passwd2').val();
        var email = $('#email').val();
        var tipo = $('#tipo').val();
        var nombre = $('#nombre').val();
        var apellido = $('#apellido').val();
        var ci = $('#ci').val();
        var tel = $('#tel').val();
        var fechaNac = $('#fechaNac').val();

        //Se pasará por ajax a la url validar.php por el método POST, los datos de los inputs.
        $.ajax({
            url:'../php/loginV3/validar.php',
            method: 'POST',
            data: {
                registerUsername: username,
                registerPassword: password,
                registerPassword2: password2,
                registerEmail: email,
                registerTipo: tipo,
                registerNom: nombre,
                registerApe: apellido,
                registerCi: ci,
                registerTel: tel,
                registerFecha: fechaNac
            },
            //En caso de tener respuesta se colocará en formato JSON.
            success: function(response){
                let respuesta = JSON.parse(response);
                let mensaje;
                let stop=0;
                //For in respuesta, que recorrerá todo este JSON y si algún valor es diferente de 0, asignará 1 a la variable stop.
                for (const key in respuesta){
                    if(respuesta.hasOwnProperty(key)){
                        if(respuesta[key]!=0){
                            stop = 1;
                            break;
                        }
                    }
                }
                //Si stop es igual a 0, o sea ningún error detectado.
                if(stop==0){
                    //El cuadro de mensajes se hará verde y mostrará "usuario creado exitosamente".
                    document.getElementById("mensajesRegistro").style.color= "green";
                    document.getElementById("mensajesRegistro").innerHTML="Usuario creado exitosamente."
                    //Se vacían los inputs.
                    document.getElementsByClassName("info-inputs")[0].value = "";
                    document.getElementsByClassName("info-inputs")[1].value = "";
                    document.getElementsByClassName("info-inputs")[2].value = "";
                    document.getElementsByClassName("info-inputs")[3].value = "";
                    document.getElementsByClassName("info-inputs")[5].value = "";
                    document.getElementsByClassName("info-inputs")[6].value = "";
                    document.getElementsByClassName("info-inputs")[7].value = "";
                    document.getElementsByClassName("info-inputs")[8].value = "";
                    document.getElementsByClassName("info-inputs")[9].value = "";
                }
                //Si stop es diferente de 0, o sea hay errores.
                else{
                    //errores, con las claves de respuesta y los mensajes de error.
                    let errores = {
                        "vacio":"Uno o más campos están vacíos.",
                        "prohibidos":"Uno o más campos contienen caracteres prohibidos.",
                        "useremail":"El nombre de usuario no puede ser un correo electrónico.",
                        "passmatch":"Las contraseñas ingresadas no coinciden.",
                        "email":"El email ingresado no es válido.",
                        "existente":"Ya existe una cuenta con ese nombre de usuario o correo.",
                        "userlength":"El nombre de usuario debe ser de entre 5 y 16 caracteres.",
                        "spaces":"El nombre de usuario y la contraseña no pueden contener espacios.",
                        "passwordlength":"La contraseña debe ser de entre 8 y 24 caracteres.",
                        "userpass":"El nombre de usuario no puede ser igual a la contraseña.",
                        "nombrelength":"El nombre debe ser de entre 2 y 24 caracteres.",
                        "apellidolength":"El apellido debe ser de entre 3 y 30 caracteres.",
                        "cilength":"La cedula debe ser de 8 caracteres.",
                        "ci":"La cédula ingresada no es correcta, debe estar escrita sin puntos ni guiones.",
                        "cedulaexistente":"Ya existe un usuario con el número de cédula ingresado",
                        "tellength":"El número de teléfono debe ser de 8 o 9 caracteres.",
                        "tel":"El número de teléfono no es válido.",
                        "celularexistente":"Ya existe un usuario con el número de teléfono ingresado",  
                        "edad":"La edad no es válida.",
                        "errorSuspendido":"Por favor ingresar un dato válido para 'suspendido'.",
                        "errorTipo":"Por favor ingresar un dato válido para 'Tipo'.",
                        "errorCargaUser":"Error al cargar el usuario."
                    };
                    let mensaje;
                    //For in de respuesta
                    for (const key in respuesta){
                        if(respuesta.hasOwnProperty(key)){
                            //Si el actual es igual a 1
                            if(respuesta[key] == 1){
                                //Variable mensaje con el error en la key en la que respuesta tenga valor 1.
                                let mensaje = errores[key];
                                //Se hace rojo el p de mensajesRegistro y se coloca el mensaje "Error: (mensaje de error)".
                                document.getElementById("mensajesRegistro").style.color= "red";
                                document.getElementById("mensajesRegistro").innerHTML = "Error: "+mensaje;
                            }
                        }
                    }
                }
                
            },
            //En caso de error.
            error: function(){
                //Se muestra en consola un error con la palabra error.
                console.error("error");
            },
            //El tipo de dato es texto.
            dataType: 'text'
        });
    }
});
});

$(document).ready(function(){
    document.getElementById("buscarUser").addEventListener("click", function(event){
        event.preventDefault()
    });
    $("#buscarUser").on("click", function(){
        let usuario = $("#usuarioModificar").val();
        $.ajax({
            url:'../php/loginV3/validarMiCuenta.php',
            method: 'POST',
            data:{
                buscarUsuario:"TRUE",
                usuario: usuario,
            },
            datatype:'text',
            success:function(response){
                let respuesta = JSON.parse(response);
                if(respuesta.status==1){
                    document.getElementById("telNum").value=respuesta.telNum;
                    document.getElementById("selectTipo").value=respuesta.tipo;
                    document.getElementById("emailMod").value=respuesta.correo;
                    document.getElementById("ciudad").value=respuesta.ciudad;
                    document.getElementById("calle1").value=respuesta.calle1;
                    document.getElementById("calle2").value=respuesta.calle2;
                    document.getElementById("calle3").value=respuesta.calle3;
                    document.getElementById("nro").value=respuesta.nro;
                    document.getElementById("user-mod-mensajes").innerHTML="&nbsp";
                }
                else if(respuesta.status==0){
                    document.getElementById("user-mod-mensajes").style.color="red";
                    document.getElementById("user-mod-mensajes").innerHTML="Usuario no encontrado.";
                }
                setTimeout(() => {
                    document.getElementById('user-mod-mensajes').innerHTML="&nbsp";
                }, 5000);
            }
        });
    });
    $("#modificarEnviar").on("click", function(){
        let usuario = $("#usuarioModificar").val();
        let telNum = $("#telNum").val();
        let selectTipo = $("#selectTipo").val();
        let emailMod = $("#emailMod").val();
        let ciudad = $("#ciudad").val();
        let calle1 = $("#calle1").val();
        let calle2 = $("#calle2").val();
        let calle3 = $("#calle3").val();
        let nro = $("#nro").val();
        let newPass = $("#newPassword").val();

        $.ajax({
            url:'../php/loginV3/validarMiCuenta.php',
            method: 'POST',
            data:{
                modificarUsuario:"TRUE",
                usuario: usuario,
                telNum: telNum,
                selectTipo: selectTipo,
                emailMod: emailMod,
                ciudad: ciudad,
                calle1: calle1,
                calle2: calle2,
                calle3: calle3,
                nro: nro,
                newPass: newPass
            },
            datatype:'text',
            success:function(response){
                let respuesta = JSON.parse(response);
                        let mensaje;
                        //Se declara la variable stop para usar luego.
                        let stop=0;
                        //For in de respuesta.
                        for (const key in respuesta){
                            if(respuesta.hasOwnProperty(key)){
                                //Si la respuesta en el índice actual es diferente de 0.
                                if(respuesta[key]!=0){
                                //Se asigna 1 a la variable stop y se rompe el bucle.
                                stop = 1;
                                break;
                            }
                        }
                }    
                //Si todo sale bien y stop es igual a 0.
                if(stop==0){
                    //Se cambia el color de mensajes a verde.
                    document.getElementById("user-mod-mensajes").style.color = "green";
                    document.getElementById("user-mod-mensajes").innerHTML = "Usuario modificado correctamente";
                }
                //Si el valor de la variable stop es diferente de 0
                else{
                    //Se crea "errores" con las mismas claves de respuesta y los mensajes de error.
                    let errores = {
                        "vacio":"Uno o más campos están vacíos.",
                        "prohibidos":"Uno o más campos contienen caracteres prohibidos.",
                        "email": "El nuevo correo no es válido.",
                        "existentecorreo": "El correo ingresado ya está en uso.",
                        "celularexistente": "El número de teléfono ingresado ya está en uso.",
                        "tellength": "El número de teléfono debe tener 9 dígitos.",
                        "tel": "El número de teléfono no es válido.",
                        "ciudadLength": "El formato de ciudad no es válido. Debe tener entre 2 y 50 caracteres.",
                        "calle1length": "El formato de calle 1 no es válido. Debe tener entre 2 y 50 caracteres.",
                        "calle2length": "El formato de calle 2 no es válido. Debe tener entre 2 y 50 caracteres.",
                        "calle3length": "El formato de calle 3 no es válido. Debe tener entre 2 y 50 caracteres.",
                        "calle1length": "El formato de número/descripción no es válido. Debe tener menos de 200 caracteres.",
                        "updateerror": "Ha ocurrido un error al actualizar los datos. Intentalo de nuevo más tarde.",
                        "passwordError":"Error al cambiar contraseña.",
                        "userpass": "La contraseña no puede ser igual al nombre de usuario.",
                        "passwordlength":"La contraseña debe ser de entre 8 y 24 caracteres.",
                        "tuInfo": "Los datos ingresados son los actuales.   "
                        };
                    let mensaje;
                    //For in respuesta
                    for (const key in respuesta){
                        if(respuesta.hasOwnProperty(key)){
                            ///Si respuesta en key es 1.
                            if(respuesta[key] == 1){
                                //Se asigna a la variable mensaje el error en la key que sea 1 en respuesta.
                                let mensaje = errores[key];
                                //Se cambia a color rojo "mensajes".
                                document.getElementById("user-mod-mensajes").style.color = "red";
                                //Se asigna a mensajes el valor "Error: (mensaje de error)".
                                document.getElementById("user-mod-mensajes").innerHTML = "Error: "+mensaje;
                            }
                        }
                    }
                }
                setTimeout(() => {
                    document.getElementById('user-mod-mensajes').innerHTML="&nbsp";
                }, 5000);
            }
        });
    });
});
    </script>
        <!-- Ver catálogo -------------------------------------------------------------------------------------- -->
        <div id="ver-prods-container" class="function-containers">
            <form id='search-bar'>
                <input type="text" id='barra-busqueda' placeholder='Buscar...'>
                <input type="button" id='buscar-submit' value='Buscar'>
                <input type="button" value='Refrescar' id='refresh'>
            </form>
    <script>
        function showAlert(id){
            let cartel = document.getElementsByClassName("del-confirm-2")[0];
            let title = document.getElementById("title");
            let btn_eliminar = document.getElementById("del-confirm-eliminar");
            let btn_cancelar = document.getElementById("del-confirm-cancelar")
            cartel.style.display="flex";
            
            btn_eliminar.onclick = () => delProd(id);
            btn_cancelar.onclick = () => cartel.style.display = 'none';
        }
        function delProd(id_prod){
            let eliminar = 1;
            $.ajax({
                //Ajax hará referencia al url '../php/loginV3/validarProds.php'
                url:'../php/loginV3/validarProds.php',
                //Por el método POST
                method: 'POST',
                //Los datos enviados serán en un objeto.
                data: {
                    eliminarProd: eliminar,
                    idProd: id_prod
                },
                success: function(response){
                    let respuesta = JSON.parse(response);
                    if(respuesta.status==1){
                        document.getElementById("mensajesProdEliminar").style.color="green";
                        document.getElementById("mensajesProdEliminar").innerHTML="Producto eliminado correctamente";
                        setTimeout(() => {
                            document.getElementsByClassName("del-confirm-2")[0].style.display='none';
                            document.getElementById("mensajesProdEliminar").innerHTML="&nbsp";
                            refresh();
                            let cantPags = $("#cantProds").val()/15;
                            if(cantPags%1 != 0){
                                cantPags = parseInt(cantPags) + 1;
                            }
                            if(pag > cantPags){
                                pag--;
                                refresh();
                            }
                        }, 1000);
                    }
                    else if(respuesta.status==0){
                        document.getElementById("mensajesProdEliminar").style.color="red";
                        document.getElementById("mensajesProdEliminar").innerHTML="Error al eliminar el producto";
                        setTimeout(() => {
                            document.getElementsByClassName("del-confirm-2")[0].style.display='none';
                            document.getElementById("mensajesProdEliminar").innerHTML="&nbsp";
                            refresh();
                        }, 1000);
                    }else if(respuesta.status==2){
                        document.getElementById("mensajesProdEliminar").style.color="red";
                        document.getElementById("mensajesProdEliminar").innerHTML="Error al eliminar el producto. Este ya fue vendido 1 o más veces";
                        setTimeout(() => {
                            document.getElementsByClassName("del-confirm-2")[0].style.display='none';
                            document.getElementById("mensajesProdEliminar").innerHTML="&nbsp";
                            refresh();
                        }, 1000);
                    }
                }
            });
        }
        let barraBusqueda = document.getElementById("barra-busqueda");
        barraBusqueda.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
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
                    refreshADM:"TRUE",
                    busqueda: busqueda,
                    pag: pag
                },
                success:function(response){
                    document.getElementById("ver-prods-container").innerHTML="<form id='search-bar' action=''><input type='text' id='barra-busqueda' name='buscar' placeholder='Buscar...'><input type='button' id='buscar-submit' value='Buscar'><input type='button' value='Refrescar' id='refresh'></form>"+response+"<div id='change-page-container'><button id='change-page-previous'>Anterior</button><button id='change-page-next'>Siguiente</button></div>";
                    document.getElementById("refresh").addEventListener("click", function(){
                        refresh();
                    });

                    $("#buscar-submit").on("click", () => {
                        busqueda = $("#barra-busqueda").val();
                        refresh();    
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
        refresh();
        $(document).ready(function(){

            $("#refresh").on("click", refresh);

            $("#buscarProd").on("click", function(){
                let idProd = $("#productoModificar").val();
                $.ajax({
                    url:'../php/loginV3/validarProds.php',
                    method:"POST",
                    data: {
                        buscarProdModificar:"TRUE",
                        id:idProd
                    },
                    success:function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status==1){
                            document.getElementById("prod-mod-mensajes").innerHTML="&nbsp";
                            document.getElementById("nombProdMod").value=respuesta.nombre;
                            document.getElementById("precioProdMod").value=respuesta.precio;
                            document.getElementById("catProdMod").value=respuesta.categoria;
                            document.getElementById("descuentoProdMod").value=respuesta.descuento;
                            document.getElementById("descripcionProdMod").value=respuesta.descripcion;
                            document.getElementById("minStockProdMod").value=respuesta.min_stock;
                            document.getElementById("label-prodMod-src").innerHTML="<i class='fa-solid fa-upload'></i>";
                            document.getElementById("hidden-input-prodMod").value="";
                            
                        }
                        if(respuesta.status==0){
                            document.getElementById("prod-mod-mensajes").style.color="red";
                            document.getElementById("prod-mod-mensajes").innerHTML="Producto no encontrado.";
                        }
                    }
                });
            });
            $("#buscar-submit").on("click", () => {
                busqueda = $("#barra-busqueda").val();
                refresh();    
            });
            $("#modificarProdEnviar").on("click", function(){
                // let idProd = $("#productoModificar").val();
                let formulario = new FormData($("#prod-mod-container")[0]);
                $.ajax({
                    url:'../php/loginV3/validarProds.php',
                    method:"POST",
                    data: formulario,
                    processData: false,
                    contentType:false,
                    
                    success:function(response){
                        //Se crea una variable a la que se asignará la respuesta en formato JSON.
                        let respuesta = JSON.parse(response);
                        let mensaje;
                        //Se declara la variable stop para usar luego.
                        let stop=0;
                        //For in de respuesta.
                        for(const key in respuesta){
                            if(respuesta.hasOwnProperty(key)){
                                //Si la respuesta en el índice actual es diferente de 0.
                                if(respuesta[key]!=0){
                                    //Se asigna 1 a la variable stop y se rompe el bucle.
                                    stop = 1;
                                break;
                                }
                            }
                        }    
                        //Si todo sale bien y stop es igual a 0.
                        if(stop==0){
                            //Se cambia el color de mensajes a verde.
                            document.getElementById("prod-mod-mensajes").style.color = "green";
                            //Se coloca en mensajes el mensaje "Producto cargado con éxito".
                            document.getElementById("prod-mod-mensajes").innerHTML = "Producto modificado con éxito";
                            setTimeout(()=>{document.getElementById("prod-mod-mensajes").innerHTML = "&nbsp";},3000);
                            //Se vacían todos los inputs del formulario.
                            for(let input of document.getElementsByClassName('prod-mod-inputs')){
                                input.value = "";
                            }     
                            document.getElementById("label-prodMod-src").innerHTML = "<i class='fa-solid fa-upload'></i>";
                            document.getElementById("prod-mod-src").innerHTML = "";
                            document.getElementById("label-prodMod-src").value = "";
                            document.getElementById("hidden-input-prodMod").innerHTML = "";
                            document.getElementById("hidden-input-prodMod").value = "";
                        }
                        //Si el valor de la variable stop es diferente de 0
                        else{
                            //Se crea "errores" con las mismas claves de respuesta y los mensajes de error.
                            let errores = {
                            "vacio":"Uno o más campos están vacíos.",
                            "prohibidos":"Uno o más campos contienen caracteres prohibidos.",
                            "errorCarga":"Ha ocurrido un error con la base de datos.",
                            'precioNegativo': "El precio no puede ser menor a 1.",
                            'minStockNegativo': "El stock de seguridad debe ser 0 o más.",
                            'descuentoInvalido': "El descuento no puede ser mayor a 99 ni menor a 0."
                        };
                            let mensaje;
                            //For in respuesta
                            for (const key in respuesta){
                                if(respuesta.hasOwnProperty(key)){
                                    ///Si respuesta en key es 1.
                                    if(respuesta[key] == 1){
                                        //Se asigna a la variable mensaje el error en la key que sea 1 en respuesta.
                                        let mensaje = errores[key];
                                        //Se cambia a color rojo "mensajes".
                                        document.getElementById("prod-mod-mensajes").style.color = "red";
                                        //Se asigna a mensajes el valor "Error: (mensaje de error)".
                                        document.getElementById("prod-mod-mensajes").innerHTML = "Error: "+mensaje;
                                        setTimeout(()=>{document.getElementById("prod-mod-mensajes").innerHTML = "&nbsp";},2000);
                                    }
                                }
                            }
                        } 
                    },
                });
            });
        });
    </script>
        </div>
    
        <!-- Ver gestiones -------------------------------------------------------------------------------------- -->            
        <div id='gestiones-container' class='function-containers'>
        <?php
                if(isset($_GET['gestion'])){
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', ()=>{
                                document.getElementById('user-add-container').classList.remove('function-in');
                                document.getElementById('gestiones-container').classList.add('function-in');
                            });
                                </script>";
                }
            ?>
                <form action='panel_adm.php?gestion' id='col-select-container'>
                    <label>Código <input class='checks-col' type="checkbox" name="col-gestion" id="cod"></label>
                    <label>Usuario <input class='checks-col' type="checkbox" name="col-nomb" id="usu"></label>
                    <label>Acción <input class='checks-col' type="checkbox" name="col-accion" id="acc"></label>
                    <label>Información <input class='checks-col' type="checkbox" name="col-info" id="info"></label>
                    <label>Fecha <input class='checks-col' type="checkbox" name="col-fecha" id="fecha"></label>
                    <label>ID Producto <input class='checks-col' type="checkbox" name="col-id_prod" id="id_prod"></label>
                    <label>Producto <input class='checks-col' type="checkbox" name="col-prod" id="prod"></label>
                    <input type="button" value='Aplicar' id='col-select-btn'>
                </form>
                <form id='filtro-gestion' method='post' action='panel_adm.php?gestion'>
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
                <form id='clear-filters' action="panel_adm.php?gestion" method='POST'>
                    <input type="hidden" name='filtros' value=''>
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
            <!--Alerta de confirmación de eliminación de producto-->
            <div class='del-confirm-2'>
                <h2 id="title">¿Eliminar el producto?</h2>
                <div>
                    <button id='del-confirm-eliminar' class='alerta-buttons'>Eliminar</button>
                    <button id='del-confirm-cancelar' class='alerta-buttons'>Cancelar</button>
                </div>
                <p id='mensajesProdEliminar'> &nbsp</p>
            </div>

    </div> 
    <script src="../scripts/panel_adm_script.js"></script>
    
    <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>
</body>
</html>