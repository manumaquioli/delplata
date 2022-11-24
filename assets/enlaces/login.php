<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/login-styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="icon" href="../iconos/Captura.PNG">
    <title>Inicio de sesión - Indumentarias del Plata</title>
</head>
<body>
    <img src="../imagenes/surfing-926822_1920.jpg" id="fondo">
    <img src="../imagenes/man-3960620_1920.jpg" id='fondo-movil'>
    <form action="" method="POST" id="container">
        <img src="../iconos/user.png" alt="" id="icon">
        <h2>Indumentarias del Plata</h2>
        <input type="text" id="nombre" placeholder="Ingrese usuario" name="loginUsername">
        <input type="password" id="passwd" placeholder="Ingrese contraseña" name="loginPassword">
        <input type="button" id='confirmar' value='Confirmar' name='confirmar'>
        <a href="register.php">¿No tienes una cuenta? ¡Regístrate! </a>
        <a href="../../index.php#footer" >¿Problemas para inciar sesión? </a>
        <p id="errores"> &nbsp </p>
    </form>
<script>
$(document).ready(function(){
    $('#confirmar').on('click', function a (){log()});
    $(document).keyup(function (e) {
    if ((e.keyCode == 13)) {
        log();
    }
});
    function log(){
        var username = $('#nombre').val();
        var password = $('#passwd').val();
        $.ajax({
            url:'../php/loginV3/validarLogin.php',
            method: 'POST',
            data: {
                loginUsername: username,
                loginPassword: password
            },
            success: function(response){
                let respuesta = JSON.parse(response);
                if(respuesta.status==1){                    
                    window.location=respuesta.tipo;
                }
                else if(respuesta.vacio==1){
                    document.getElementById("errores").innerHTML = "Espacios vacíos";
                    document.getElementById("passwd").value = "";
                }
                else if(respuesta.susp==1){
                    document.getElementById("errores").innerHTML = "Usuario suspendido";
                    document.getElementById("passwd").value = "";
                }

                else if(respuesta.status==0){
                    document.getElementById("errores").innerHTML = "Logueo incorrecto";
                    document.getElementById("passwd").value = "";
                }
            },
            error: function(){
                console.error("error");
            },
        
            dataType: 'text'
        });
    }
});

</script>

        <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>

</body>
</html>