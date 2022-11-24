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
//Si no hay ningún usuario logueado:
    if($_SESSION['logUsuario'] == null){
        //Se cierra sesión y se redirecciona a login.
        session_unset();
        header("location:login.php");
    }
    //Si el usuario está suspendido
    if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
        //Se cierra sesión y se redirecciona a login.
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
    <link rel="icon" href="../iconos/Captura.PNG">
    <link rel="stylesheet" href="../estilos/mi_cuenta-styles.css">
    <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <title>Mi cuenta - Indumentarias del Plata</title>
</head>
<body>
<?php
if(empty($_SESSION['logUsuario'])){
    header("location:login.php");
}
else{
    echo '<i class="fa-solid fa-bars ham-btn"></i>
    <header id="navbar">
    <a href="../../index.php" id="logo"><img src="../iconos/Captura.PNG" alt=""></a>
    
    <div id="text-header-container">
        Gestión de perfil
    </div>
    
    <div id="user-container" onclick="deslizar_info()">';
        echo $_SESSION['logUsuario'].'<i id="user-icon" class="fa-solid fa-user"></i>
    </div>
    
    </header>';
    if($_SESSION['logTipo'] == "cliente"){
        echo '<form id="user-info" action="panel_adm.php?logout" method="post">
            <div id="historial"><a href="mis_compras.php">Mis Compras</a></div>
            <input type="submit" id="logout" readonly="readonly" value="Cerrar sesión" name="logout">
        </form>';
    }else if($_SESSION['logTipo'] == 'adm'){
        echo '<form id="user-info" action="panel_adm.php?logout" method="post">
            <div id="historial"><a href="panel_adm.php">Panel</a></div>
            <input type="submit" id="logout" readonly="readonly" value="Cerrar sesión" name="logout">
        </form>';
    }else if($_SESSION['logTipo'] == 'empleado'){
        echo '<form id="user-info" action="panel_adm.php?logout" method="post">
            <div id="historial"><a href="panel_emp.php">Panel</a></div>
            <input type="submit" id="logout" readonly="readonly" value="Cerrar sesión" name="logout">
        </form>';
    }

    echo '<div id="panel">
        <div id="user-information" class="panel-items">Ver mi información</div>';
        if($_SESSION['logTipo'] == "cliente"){   
           echo '<div id="user-purchases" class="panel-items">Mis Compras</div>
            <div id="user-history" class="panel-items">Historial</div>';
        }
        echo '<div id="change-email" class="panel-items">Modificar datos personales</div>
        <div id="change-passwd" class="panel-items">Seguridad</div>
    </div>';
}
    ?>

    <div id='content-container'>

        <div id='user-information-container' class='content-containers function-in'>
            <h2>Mi perfil</h2>
        <?php
            $arrayDatosUser = info_user($connDB, $_SESSION['logUsuario']);
            $user = $arrayDatosUser[0]['nomb_usu'];
            $cedula = $arrayDatosUser[0]['ci'];
            $pass = $arrayDatosUser[0]['pass'];
            
            $arrayDatosPer = info_persona($connDB, $cedula);
            $arrayDatosTel = info_tel($connDB, $cedula);

            foreach($arrayDatosPer as $valorPer){
                echo "<label><b>Nombre:</b> $valorPer[nombre] $valorPer[apellido]</label>";
                echo "<label><b>CI:</b> $valorPer[ci]</label>";
                echo "<label><b>Correo electrónico:</b> $valorPer[correo] <a onclick='changeData()' href='#' id='correo-enlace'>Cambiar</a></label>";
                echo "<label><b>Fecha de nacimiento (YYYY-MM-DD):</b> $valorPer[fecha_nac]</label>";    
                if(!isset($valorPer['ciudad']) && !isset($valorPer['calle1']) && !isset($valorPer['calle2']) && !isset($valorPer['calle3']) ){
                    echo "<label><b>Ubicacion:</b> No definida <a onclick='changeData()' href='#' id='ubicacion-enlace'>Añadir</a></label>";
                }else{
                    echo "<label><b>Ubicacion: <br> &nbsp Ciudad:</b> $valorPer[ciudad] <br> &nbsp <b>calle principal:</b> $valorPer[calle1] <br>
                    &nbsp <b>calle 2:</b> $valorPer[calle2] <br> &nbsp <b>calle 3:</b> $valorPer[calle3] <br> &nbsp <b>nro:</b> $valorPer[nro]<br> <a onclick='changeData()' href='#' id='ubicacion-enlace'>Editar</a></label>";
                }
            }
            $nums = ""; 
            foreach ($arrayDatosTel as $valorTel) {
                $nums .= "$valorTel[num], " ;    
            }
            $nums = rtrim($nums, ", ");
            echo "<label><b>Número(s) de teléfono:</b> $nums</label>";
            foreach($arrayDatosUser as $valorUser){
                echo "<label><b>Nombre de usuario:</b> $valorUser[nomb_usu]</label>";
                echo "<label><b>Tipo de cuenta:</b> $valorUser[tipo]</label>";
            }

        ?>
        </div>
        <?php
            if($_SESSION['logTipo'] == "cliente"){
                $compras = display_compras($connDB, $_SESSION['logUsuario']);
                echo "<div id='user-purchases-container' class='content-containers'>";
                if(empty($compras)){
                    echo "<h1>Aún no has realizado ninguna compra.";
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
                            echo "<td id='first-column'>$valor[id_compra]  <form action='detalle_compra.php' method='post'><input type='hidden' name='id' value='{$valor['id_compra']}'><input type='submit' class='btn-detalles' value='Ver detalles'></form>"/* <a href='detalle_compra.php?id={$valor['id_compra']}'>Ver detalles</a*/."</td>";
                            echo "<td> ".'U$D '."$valor[monto]</td>";
                            echo "<td>$valor[fecha]</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                    echo "</div>
                <div id='user-history-container' class='content-containers'>";
                //Función getHistory definida en conexionbd.php
                    $historial = getHistory($connDB, $_SESSION['logUsuario']);
                    if(empty($historial) || $historial=="ERROR" || count($historial)<1){
                        echo "<h2>El historial está vacío.</h2>";
                    }else if($historial!="ERROR"){
                        foreach($historial as $value){
                            echo "<div class='history-item' id='item-{$value['id_prod']}'>";
                                echo "<img src='../imagenes/$value[img]' class='prod-img'>";
                                echo "<p>".$value['nomb_prod']."</p>";
                                echo "<p>".$value['descripcion']."</p>";
                                echo "<button class='btn1' id='btn-{$value['id_prod']}'><i class='fa-solid fa-xmark' id='btn-{$value['id_prod']}'></i></button>";
                            echo "</div>";
                        }
                    }
                echo "</div>";
            }
        ?>
        <script>
            $(document).ready(function(){
                $(".btn1").on("click", function(event){
                //Variable extraerId: /(\d+)/g significa que extraerá todos los números.
                let extraerId = /(\d+)/g;
                //Variable idTarget, guardará la id del botón clickeado.
                let idTarget = event.target.id;
                idTarget = idTarget.match(extraerId).toString();
                    $.ajax({
                        //Este ajax irá al url '../php/loginV3/validarMiCuenta.php'
                        url:'../php/loginV3/validarMiCuenta.php',
                        //Por el método POST
                        method: 'POST',
                        data:{
                            removerHistorial: "TRUE",
                            idRemover:idTarget
                        },
                        success:function(response){
                            if(response == 1){
                                document.getElementById("item-"+idTarget).remove();
                            }else{
                                alert("Error al borrar historial.");
                            }
                        }
                    });
                });
            });
        </script>
        <?php
        $sqlCi = mysqli_query($connDB, "SELECT ci FROM usuario WHERE nomb_usu='{$_SESSION['logUsuario']}'");
        $arrayCi = array();
        while($fila = mysqli_fetch_array($sqlCi)){
            $arrayCi[] = $fila;
        }
        $ci = $arrayCi[0]['ci'];
        
        $sqlPer = mysqli_query($connDB, "SELECT * FROM persona WHERE ci='$ci'");
        $arrayPer = array();
        while($fila = mysqli_fetch_array($sqlPer)){
            $arrayPer[] = $fila;
        }
        $sqlTel = mysqli_query($connDB, "SELECT num FROM tel WHERE ci='$ci'");
        $arrayTel = array();
        while($fila = mysqli_fetch_array($sqlTel)){
            $arrayTel[] = $fila;
        }
        $num = $arrayTel[0]['num'];
        //Datos personales
        echo "
        <form action='../php/loginV3/validarMiCuenta.php?validarNewEmail' method='post' id='change-data-container' class='content-containers'>
            <div><b>Número de teléfono:</b> <input type='number' name='telNum' value='$num' id='newNum' class='change-data-inputs'></div>
            <div><b>Correo:</b> <input type='text' name='newEmail' value='{$arrayPer[0]['correo']}' id='newEmail' class='change-data-inputs' required><br><br><br></div>
            <div><b>Ciudad:</b> <input type='text' name='ciudad' onkeydown='return /[a-z]/i.test(event.key)' value='{$arrayPer[0]['ciudad']}' id='newCiudad' class='change-data-inputs'></div>
            <div><b>Calle 1:</b> <input type='text' name='calle1' value='{$arrayPer[0]['calle1']}' id='newCalle1' class='change-data-inputs'></div>
            <div><b>Calle 2:</b> <input type='text' name='calle2' value='{$arrayPer[0]['calle2']}' id='newCalle2' class='change-data-inputs'></div>
            <div><b>Calle 3:</b> <input type='text' name='calle3' value='{$arrayPer[0]['calle3']}' id='newCalle3' class='change-data-inputs'></div>
            <div><b>Número/descripción:</b> <input type='text' name='nro' value='{$arrayPer[0]['nro']}' id='newNro' class='change-data-inputs'></div><br>
            <div><b>Ingresar contraseña:</b> <input type='password' name='passNewEmail' class='change-data-inputs' id='passNewData' required></div>
            <br>
            <input type='button' value='Modificar' id='modificarDatosButton'>
            <p id='mensajesModificar'>&nbsp</p>
        </form>";
        ?>

        <!-- Seguridad ------------------------------------------------------------------------------------------- -->
        <div id='security-container' class='content-containers'>
            <!-- Cambiar contraseña -->
            <form action='../php/loginV3/validarMiCuenta.php?validarNewPass' method='post'>
                <h2>Cambiar contraseña <i class="fa-solid fa-lock"></i></h2>
                <div><b>Nueva contraseña: </b><input type='password' name='newPass' id="newPass1" required></div>
                <div><b>Nueva contraseña (otra vez): </b><input type='password' name='newPass2' id="newPass2" required></div>
                <div><b>Contraseña actual: </b><input type='password' name='passNewPass' id="passNewPass" required></div>
                <?php
                echo "<input type='hidden' name='pass' value='$pass'>";
                echo "<input type='hidden' name='username' value='$user'>";
                ?>
                <input type='button' id="cambiarPassButton" value="Cambiar">
                <p id="mensajesCambiarPass">&nbsp</p>
            </form>
            
            <!-- Suspender cuenta -->
            <form action="../php/loginV3/validarMiCuenta?validarSuspendido" method='post'>
                <h2>Suspender cuenta <i class="fa-solid fa-triangle-exclamation"></i></h2>
                <div><b>Contraseña: </b><input type='password' name='passwdSuspender' id="passSusp1" required></div>
                <div><b>Contraseña (otra vez): </b><input type='password' name='passwdSuspender2' id="passSusp2" required></div>
            <?php
                echo "<input type='hidden' name='username' value='$user'>";
            ?>
                <input type="button" id="suspButton" value='Suspender'>
                <p id="mensajesSusp">&nbsp</p>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#suspButton").on("click", function(){
                let pass1 = $("#passSusp1").val();
                let pass2 = $("#passSusp2").val();
                $.ajax({
                    //Ajax hará referencia al url '../php/loginV3/validarMiCuenta.php'
                    url:'../php/loginV3/validarMiCuenta.php',
                    //Por el método POST
                    method: 'POST',
                    data:{
                        autosuspender: "TRUE",
                        suspPass: pass1,
                        suspPass2: pass2,
                    },
                    datatype: "text",
                    success: function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status == 1){
                            window.location="../php/loginV3/mensajes.php?suspendido";
                        }
                        else if(respuesta.passMatch == 1){
                            document.getElementById("mensajesSusp").style.color="red";
                            document.getElementById("mensajesSusp").innerHTML="Las contraseñas no coinciden.";
                        }
                        else if(respuesta.errPass == 1){
                            document.getElementById("mensajesSusp").style.color="red";
                            document.getElementById("mensajesSusp").innerHTML="La contraseña ingresada no es correcta.";
                        }
                    }
                });
            });

            $("#cambiarPassButton").on("click", function(){
                let newPass1 = $("#newPass1").val();
                let newPass2 = $("#newPass2").val();
                let passNewPass = $("#passNewPass").val();

                $.ajax({
                    //Ajax hará referencia al url '../php/loginV3/validarMiCuenta.php'
                    url:'../php/loginV3/validarMiCuenta.php',
                    method: 'POST',
                    data:{
                        validarNewPass:"TRUE",
                        newPass:newPass1,
                        newPass2:newPass2,
                        passNewPass:passNewPass
                    },
                    datatype:"text",
                    success: function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status==1){
                            window.location="../php/loginV3/mensajes.php?contraseñaCambiada";
                        }
                        else if(respuesta.passMatch == 1){
                            document.getElementById("mensajesCambiarPass").style.color="red";
                            document.getElementById("mensajesCambiarPass").innerHTML="Las contraseñas no coinciden.";
                        }
                        else if(respuesta.errPass == 1){
                            document.getElementById("mensajesCambiarPass").style.color="red";
                            document.getElementById("mensajesCambiarPass").innerHTML="La contraseña actual no es la correcta";
                        }
                        else if(respuesta.tuPass == 1){
                            document.getElementById("mensajesCambiarPass").style.color="red";
                            document.getElementById("mensajesCambiarPass").innerHTML="La contraseña nueva es la misma que la actual";
                        }
                        else if(respuesta.passInv == 1){
                            document.getElementById("mensajesCambiarPass").style.color="red";
                            document.getElementById("mensajesCambiarPass").innerHTML="La contraseña nueva no es válida. Debe tener entre 8 y 24 caracteres.";
                        }
                    }
                });
            });
            $("#modificarDatosButton").on("click", function(){
                let newEmail = $("#newEmail").val();
                let newTel = $("#newNum").val();
                let newCiudad = $("#newCiudad").val();
                let newCalle1 = $("#newCalle1").val();
                let newCalle2 = $("#newCalle2").val();
                let newCalle3 = $("#newCalle3").val();
                let newNro = $("#newNro").val();
                let passNewData = $("#passNewData").val();

                $.ajax({
                    //Ajax hará referencia al url '../php/loginV3/validarMiCuenta.php'
                    url:'../php/loginV3/validarMiCuenta.php',
                    method: 'POST',
                    data:{
                        cambiarDatos: "TRUE",
                        newEmail: newEmail,
                        newTel: newTel,
                        newCiudad: newCiudad,
                        newCalle1: newCalle1,
                        newCalle2: newCalle2,
                        newCalle3: newCalle3,
                        passNewData: passNewData,
                        newNro: newNro
                    },
                    datatype:"text",
                    success: function(response){
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
                    document.getElementById("mensajesModificar").style.color = "green";
                    document.getElementById("mensajesModificar").innerHTML = "Datos personales modificados.";
                }
                //Si el valor de la variable stop es diferente de 0
                else{
                    //Se crea "errores" con las mismas claves de respuesta y los mensajes de error.
                    let errores = {
                        "vacio":"Uno o más campos están vacíos.",
                        "passincorrecta":"La contraseña ingresada no es correcta.",
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
                        "tuInfo": "La información ingresada es idéntica a la actual."
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
                                document.getElementById("mensajesModificar").style.color = "red";
                                //Se asigna a mensajes el valor "Error: (mensaje de error)".
                                document.getElementById("mensajesModificar").innerHTML = "Error: "+mensaje;
                            }
                        }
                    }
                }
                
            },
                    
                    });
            });
        });
    </script>
<script src="../scripts/mi_cuenta_script.js"></script>


<style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>

</body>
</html>