<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indumentarias del Plata</title>
</head>
<body>
<?php
    if(isset($_GET['prod_cargado'])){
       echo "<script>
            let msg = document.createElement('h2');
            msg.innerHTML = 'Producto cargado correctamente. Redireccionando...';
            document.getElementsByTagName('body')[0].appendChild(msg);
            setTimeout(()=>{
                window.location.href = '../../enlaces/panel_adm.php';
            }, 3000);
        </script>";
    }else if(isset($_GET['prod_no_cargado'])){
        echo "<script>
        let msg = document.createElement('h2');
        msg.innerHTML = 'Error al cargar el producto. Redireccionando...';
        document.getElementsByTagName('body')[0].appendChild(msg);
        setTimeout(()=>{
            window.location.href = '../../enlaces/panel_adm.php';
        }, 3000);
        </script>";
    }
    else if(isset($_GET['caracteresProhibidosProductoNuevo'])){
        echo "<script>
        let msg = document.createElement('h2');
        msg.innerHTML = 'Error al cargar el producto: caracteres prohibidos (<,>). Redireccionando...';
        document.getElementsByTagName('body')[0].appendChild(msg);
        setTimeout(()=>{
            window.location.href = '../../enlaces/panel_adm.php';
        }, 3000);
        </script>";
    }

    if(isset($_GET['prodEliminado'])){
        echo "<script>
            let msg = document.createElement('h2');
            msg.innerHTML = 'Producto eliminado correctamente. Redireccionando...';
            document.getElementsByTagName('body')[0].appendChild(msg);
            setTimeout(()=>{
                window.location.href = '../../enlaces/panel_adm.php';
            }, 3000);
        </script>";
    }else if(isset($_GET['prodNoEliminado'])){
        echo "<script>
        let msg = document.createElement('h2');
        msg.innerHTML = 'Error al eliminar el producto. Redireccionando...';
        document.getElementsByTagName('body')[0].appendChild(msg);
        setTimeout(()=>{
            window.location.href = '../../enlaces/panel_adm.php';
        }, 3000);
        </script>";
    }else if(isset($_GET['prodComprado'])){
        echo "<script>
        let msg = document.createElement('h2');
        msg.innerHTML = 'El producto fue comprado al menos una vez, no se puede eliminar. Redireccionando...';
        document.getElementsByTagName('body')[0].appendChild(msg);
        setTimeout(()=>{
            window.location.href = '../../enlaces/panel_adm.php';
        }, 3000);
        </script>";
    }

    if(isset($_GET['correoCambiado'])){
        echo "<script>
             let msg = document.createElement('h2');
             msg.innerHTML = 'Correo cambiado correctamente. Redireccionando...';
             document.getElementsByTagName('body')[0].appendChild(msg);
             setTimeout(()=>{
                 window.location.href = '../../enlaces/mi_cuenta.php';
             }, 3000);
         </script>";
     }else if(isset($_GET['correoNoCambiado'])){
         echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Error al cambiar el correo. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
             window.location.href = '../../enlaces/mi_cuenta.php';
         }, 3000);
        </script>";
     } 

     if(isset($_GET['tuCorreo'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Ya estas usando ese correo. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
             window.location.href = '../../enlaces/mi_cuenta.php';
         }, 3000);
        </script>";
     }
     
     if(isset($_GET['contraseñaIncorrecta'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Contraseña incorrecta. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
             window.location.href = '../../enlaces/mi_cuenta.php';
         }, 3000);
        </script>";
     }else if(isset($_GET['emailInvalido'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Correo electrónico no válido. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
             window.location.href = '../../enlaces/mi_cuenta.php';
         }, 3000);
        </script>";
     }

     if(isset($_GET['tuPass'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Ya estás usando esa contraseña. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
             window.location.href = '../../enlaces/mi_cuenta.php';
         }, 3000);
        </script>";
     }

     if(isset($_GET['contraseñaCambiada'])){
        echo "<script>
        let msg = document.createElement('h2');
        msg.innerHTML = 'Contraseña cambiada con éxito. Redireccionando...';
        document.getElementsByTagName('body')[0].appendChild(msg);
        setTimeout(()=>{
            window.location.href = '../../enlaces/login.php';
        }, 3000);
       </script>";
     }else if(isset($_GET['contraseñaNoCambiada'])){
        echo "<script>
        let msg = document.createElement('h2');
        msg.innerHTML = 'Error al cambiar la contraseña. Redireccionando...';
        document.getElementsByTagName('body')[0].appendChild(msg);
        setTimeout(()=>{
            window.location.href = '../../enlaces/mi_cuenta.php';
        }, 3000);
       </script>";
     }

     if(isset($_GET['contraseñaInvalida'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Contraseña no cambiada, debe tener entre 8 y 24 caracteres. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
             window.location.href = '../../enlaces/mi_cuenta.php';
         }, 3000);
        </script>";
     }

     if(isset($_GET['contraseñasNoCoinciden'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Error, las contraseñas no coinciden. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
             window.location.href = '../../enlaces/mi_cuenta.php';
         }, 3000);
        </script>";
     }

     if(isset($_GET['correoExiste'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Otro usuario ya está usando ese correo. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
             window.location.href = '../../enlaces/mi_cuenta.php';
         }, 3000);
        </script>";
     }

     if(isset($_GET['stockActualizado'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Stock actualizado correctamente. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../enlaces/panel_emp.php';
         }, 3000);
        </script>";
     }else if(isset($_GET['stockNoActualizado'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Error al actualizar el stock. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../enlaces/panel_emp.php';
         }, 3000);
        </script>";
     }else if(isset($_GET['errorStock'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Error al actualizar el stock, asegúrese de seleccionar el tipo de carga (agregar o sobreescribir). Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../enlaces/panel_emp.php';
            }, 3000);
        </script>";
     }

     if(isset($_GET['userLoaded'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Usuario creado. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../../index.php';
            }, 3000);
        </script>";
    }else if(isset($_GET['userNotLoaded'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Usuario no creado. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../../index.php';
            }, 3000);
        </script>";
     }

     if(isset($_GET['compra_realizada'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Compra realizada con éxito. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../../index.php';
            }, 3000);
        </script>";
     }else if(isset($_GET['compra_no_realizada'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Error al realizar la compra. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../../index.php';
            }, 3000);
        </script>";
     }

     if(isset($_GET['a'])){
        echo $_GET['a'];
     }
     if(isset($_GET['registrado'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Usuario registrado. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../enlaces/login.php';
            }, 3000);
        </script>";
    }
    if(isset($_GET['suspendido'])){
        echo "<script>
         let msg = document.createElement('h2');
         msg.innerHTML = 'Cuenta suspendida exitosamente. Redireccionando...';
         document.getElementsByTagName('body')[0].appendChild(msg);
         setTimeout(()=>{
            window.location.href = '../../enlaces/login.php';
            }, 3000);
        </script>";
    }    
?>
<style>
@import url("https://fonts.googleapis.com/css2?family=Signika:wght@300;400;500;600&display=swap");
h2
{
    font-family: Signika, sans-serif;
    color: #103c8a;
}
</style>
</body>
</html>