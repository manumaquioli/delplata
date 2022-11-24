<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/register-styles.css">
    <link rel="icon" href="../iconos/Captura.PNG">
    <title>Registar - Indumentarias del Plata</title>
</head>
<body>
    <img src="../imagenes/surfing-926822_1920.jpg" id="fondo">
    <img src="../imagenes/man-3960620_1920.jpg" id='fondo-movil'>
    
    <form action="" method="POST" id="container">
        <img src="../iconos/user.png" alt="" id="icon">
        <h2>Indumentarias del Plata</h2>
        <div id="inputs-container">
            <input class="info-inputs" type="text" id="username" placeholder="Nombre de usuario" name="registerUsername">
            <input class="info-inputs" type="password" id="passwd" placeholder="Contraseña" name="registerPassword">
            <input class="info-inputs" type="password" id="passwd2" placeholder="Contraseña (otra vez)" name="registerPassword2">
            <input class="info-inputs" type="text" id="email" placeholder="Correo electrónico" name="registerEmail">
            <input class="info-inputs" type="text" id="nombre" placeholder="Nombre" name="registerNom">
            <input class="info-inputs" type="text" id="apellido" placeholder="Apellido" name="registerApe">
            <input class="info-inputs" type="number" id="ci" placeholder="Cédula de identidad" name="registerCi">
            <input class="info-inputs" type="number" id="tel" placeholder="Número de teléfono" name="registerTel">
            <input class="info-inputs" type="date" id="fechaNac" placeholder="Fecha de nacimiento" name="registerFecha">
        </div>
        <input type='button' id='registrar' value='Registrar' name='confirmar'>
        <a href="login.php">¿Ya tienes una cuenta? Inicia sesión aquí</a>
        <p id="mensajes">&nbsp</p>
    </form>

<script>

$(document).ready(function(){
    $('#registrar').on('click', function a (){reg()});
    $(document).keyup(function (e) {
        if ((e.keyCode == 13)) {
            reg();
        }
    
});
    function reg(){
        var username = $('#username').val();
        var password = $('#passwd').val();
        var password2 = $('#passwd2').val();
        var email = $('#email').val();
        var nombre = $('#nombre').val();
        var apellido = $('#apellido').val();
        var ci = $('#ci').val();
        var tel = $('#tel').val();
        var fechaNac = $('#fechaNac').val();

        $.ajax({
            url:'../php/loginV3/validar.php',
            method: 'POST',
            data: {
                registerUsername: username,
                registerPassword: password,
                registerPassword2: password2,
                registerEmail: email,
                registerNom: nombre,
                registerApe: apellido,
                registerCi: ci,
                registerTel: tel,
                registerFecha: fechaNac
            },
            success: function(response){
                let respuesta = JSON.parse(response);
                let mensaje;
                let stop=0;
                for (const key in respuesta){
                    if(respuesta.hasOwnProperty(key)){
                        console.log(key + " => "+respuesta[key]);
                        if(respuesta[key]!=0){
                            stop = 1;
                            break;
                        }
                    }
                }
                if(stop==0){
                    window.location="../php/loginV3/mensajes.php?registrado";
                }
                else{
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
                        "edad":"Debés de tener 18 años o más para crearte una cuenta."
                    };
                    let mensaje;
                    for (const key in respuesta){
                        if(respuesta.hasOwnProperty(key)){
                            if(respuesta[key] == 1){
                                let mensaje = errores[key];
                                document.getElementById("mensajes").innerHTML = "Error: "+mensaje;
                            }
                        }
                    }
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