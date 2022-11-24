<?php  

    //IMPORTA LA CLASE USUARIO
    require_once "clases/usuarios.php";
    try{
        include_once "conexionbd.php";
    }
    catch(Exception $e){
        echo "Error de conexión con la base de datos.";
        return;
    }
    //Registro-------------------------------------------------------------------------------------------------------------------------------------
   $status = 0;
   $vacio = 0;
   $userFail = 0;
   $GLOBALS['passmatch'] = 0;
    //Si se encuentra la palabra "register" en el método GET:   
        if(!isset($_POST['registerTipo']) || $_POST['registerTipo'] == null){
            $tipo = "0";
        }
        else{
            $tipo = $_POST['registerTipo'];
        }
        //Verificación de posibles credenciales vacías.
        if(!empty($_REQUEST['registerUsername']) && !empty($_REQUEST['registerPassword']) && !empty($_REQUEST["registerPassword2"]) && !empty($_REQUEST['registerEmail']) && !empty($_REQUEST["registerNom"]) && !empty($_REQUEST["registerApe"]) && !empty($_REQUEST["registerCi"]) && !empty($_REQUEST["registerTel"]) && !empty($_REQUEST["registerFecha"])){
            //Verificación si coinciden o no lo escrito en los dos campos de contraseña.
            if($_REQUEST['registerPassword'] == $_REQUEST['registerPassword2']){
                $usuario = new Usuario($_POST['registerUsername'], $_POST['registerPassword'], $tipo, $_POST['registerEmail'], $_POST['registerNom'], $_POST['registerApe'], $_POST['registerCi'], $_POST['registerTel'], $_POST['registerFecha']);
                //Método ingresarUsuarioBd de Usuario (definido en la clase Usuario)
                $usuario->ingresarUsuarioBd($usuario->getUser(), $usuario->getPassword(), $usuario->getTipo(), $usuario->getEmail(), $usuario->getNombre(), $usuario->getApe(), $usuario->getCi(), $usuario->getTel(), $usuario->getFechaNac(), $usuario->getSuspendido());
            }else{
                    //Si las contraseñas ingresadas no coinciden, redirección a error de no coincidencia de contraseñas.
                    $GLOBALS['passmatch'] = 1;
                }
        }else{
            //Si algún campo está vacío, redirección a eror de campo vacío.
            $vacio = 1;
            }
            $rsp = array(
                'vacio' => $vacio,
                'prohibidos' => $GLOBALS['prohibidos'],
                'useremail' => $GLOBALS['useremail'],
                'passmatch' => $GLOBALS['passmatch'],
                'email' => $GLOBALS['email'],
                'existente' => $GLOBALS['existente'],
                'userlength' => $GLOBALS['userlength'],
                'spaces' => $GLOBALS['spaces'],
                'passwordlength' => $GLOBALS['passwordlength'],
                'userpass' => $GLOBALS['userpass'],
                'nombrelength' => $GLOBALS['nombrelength'],
                'apellidolength' => $GLOBALS['apellidolength'],
                'cilength' => $GLOBALS['cilength'],
                'ci' => $GLOBALS['ci'],
                'cedulaexistente' => $GLOBALS['cedulaexistente'],
                'tellength' => $GLOBALS['tellength'],
                'tel' => $GLOBALS['tel'],
                'celularexistente' => $GLOBALS['celularexistente'],
                'edad' => $GLOBALS['edad'],
                'errorSuspendido' => $GLOBALS['errorSuspendido'],
                'errorTipo' => $GLOBALS['errorTipo'],
                'errorCargaUser' => $GLOBALS['errorCargaUser']
            );
                $resp = json_encode($rsp);
                echo $resp;
?>

