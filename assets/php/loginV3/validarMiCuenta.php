<?php
    require_once "clases/usuarios.php";
try{
    include_once "conexionbd.php";
}
catch(Exception $e){
    echo "Error de conexión con la base de datos";
    return;
}
$GLOBALS['passIncorrecta'] = 0;

//Cambiar datos-------------------------------------------------------------------------------------------
if(isset($_POST['cambiarDatos'])){
    $GLOBALS['vacio'] = 0;
    $GLOBALS['passincorrecta'] = 0;
    $GLOBALS['tuEmail'] = 0;
    $GLOBALS['tuTel'] = 0;
    $GLOBALS['tuCiudad'] = 0;
    $GLOBALS['tuCalle1'] = 0;
    $GLOBALS['tuCalle2'] = 0;
    $GLOBALS['tuCalle3'] = 0;
    $GLOBALS['tuNro'] = 0;
    $GLOBALS['tuInfo'] = 0;

    if(!isset($_SESSION['logUsuario'])){session_start();}
    if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
        session_unset();
        return;
      }
    $sqlUser = mysqli_query($connDB, "SELECT * FROM usuario WHERE nomb_usu='{$_SESSION['logUsuario']}'");
    $arrayUser = array();
    while($fila = mysqli_fetch_array($sqlUser)){
        $arrayUser[] = $fila;
    }
    
    $sqlPer = mysqli_query($connDB, "SELECT * FROM persona WHERE ci='{$arrayUser[0]['ci']}'");
    $arrayPer = array();
    while($fila = mysqli_fetch_array($sqlPer)){
        $arrayPer[] = $fila;
    }
    $sqlTel = mysqli_query($connDB, "SELECT num FROM tel WHERE ci='{$arrayUser[0]['ci']}'");
    $arrayTel = array();
    while($fila = mysqli_fetch_array($sqlTel)){
        $arrayTel[] = $fila;
    }
    $num = $arrayTel[0]['num'];

    if(empty($_POST['passNewData']) || empty($_POST['newEmail']) || empty($_POST['newTel'])){
        $GLOBALS['vacio'] = 1;
    }
    if(password_verify($_POST['passNewData'], consultaPass($connDB, $_SESSION['logUsuario']))){
        $usuario = new Usuario ($arrayUser[0]['nomb_usu'], $arrayUser[0]['pass'], $arrayUser[0]['tipo'], $arrayPer[0]['correo'], $arrayPer[0]['nombre'], $arrayPer[0]['apellido'], $arrayUser[0]['ci'], $arrayTel[0]['num'], $arrayPer[0]['fecha_nac']);
        
        if($_POST['newEmail'] === $usuario->getEmail()){
            $GLOBALS['tuEmail'] = 1;
        }
        if($_POST['newTel'] == $usuario->getTel()){
            $GLOBALS['tuTel'] = 1;
        }
        if($_POST['newCiudad'] == $arrayPer[0]['ciudad']){
            $GLOBALS['tuCiudad'] = 1;
        }
        if($_POST['newCalle1'] == $arrayPer[0]['calle1']){
            $GLOBALS['tuCalle1'] = 1;
        }
        if($_POST['newCalle2'] == $arrayPer[0]['calle2']){
            $GLOBALS['tuCalle2'] = 1;
        }
        if($_POST['newCalle3'] == $arrayPer[0]['calle3']){
            $GLOBALS['tuCalle3'] = 1;
        }
        if($_POST['newNro'] == $arrayPer[0]['nro']){
            $GLOBALS['tuNro'] = 1;
        }
        if(isset($_POST['newCiudad'])){
            $usuario->setCiudad($_POST['newCiudad']);
        }
        if(isset($_POST['newCalle1'])){
            $usuario->setCalle1($_POST['newCalle1']);
        }
        if(isset($_POST['newCalle2'])){
            $usuario->setCalle2($_POST['newCalle2']);
        }
        if(isset($_POST['newCalle3'])){
            $usuario->setCalle3($_POST['newCalle3']);
        }
        if(isset($_POST['newNro'])){
            $usuario->setNro($_POST['newNro']);
        }
        if(isset($_POST['newEmail'])){
            $usuario->setEmail($_POST['newEmail']);
        }
        if(isset($_POST['newTel'])){
            $usuario->setTel($_POST['newTel']);
        }
        if($arrayPer[0]['correo'] == $_POST['newEmail'] 
        && $arrayTel[0]['num']==$_POST['newTel'] 
        && $arrayPer[0]['calle1']==$_POST['newCalle1'] 
        && $arrayPer[0]['calle2']==$_POST['newCalle2']
        && $arrayPer[0]['calle3']==$_POST['newCalle3']
        && $arrayPer[0]['ciudad']==$_POST['newCiudad']
        && $arrayPer[0]['nro']==$_POST['newNro']){
            $GLOBALS['tuInfo'] = 1;
        }
        if($GLOBALS['tuInfo']!=1){
            $usuario->updateUsuarioBd($arrayPer[0]['ci'], $_POST['newEmail'], $_POST['newTel'], $_POST['newCiudad'], $_POST['newCalle1'], $_POST['newCalle2'], $_POST['newCalle3'], $_POST['newNro'], "cliente", null, 1);
        }

    }else if(!password_verify($_POST['passNewData'], consultaPass($connDB, $_SESSION['logUsuario']))){
        $GLOBALS['passincorrecta'] = 1;
    }

    $rsp = array(
        'vacio' => $GLOBALS['vacio'],
        'passincorrecta' => $GLOBALS['passincorrecta'],
        'prohibidos' => $GLOBALS['prohibidos'],
        'email' => $GLOBALS['email'],
        'existentecorreo' => $GLOBALS['updExistenteCorreo'],
        'celularexistente' => $GLOBALS['updExistenteTel'],
        'tellength' => $GLOBALS['tellength'],
        'tel' => $GLOBALS['tel'],
        'ciudadlength' => $GLOBALS['ciudadlength'],
        'calle1length' => $GLOBALS['calle1length'],
        'calle2length' => $GLOBALS['calle2length'],
        'calle3length' => $GLOBALS['calle3length'],
        'nrolength' => $GLOBALS['nrolength'],
        'updateerror' => $GLOBALS['updateerror'],
        'tuInfo' => $GLOBALS['tuInfo']
    );
    echo json_encode($rsp);
}

//Cambiar contraseña-------------------------------------------------------------------------------------------
if(isset($_POST['validarNewPass'])){
    session_start();
    if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
        session_unset();
        return;
      }
    //Si las 2 nuevas contraseñas son iguales.
    if($_POST['newPass'] == $_POST['newPass2']){
            $tuPass = 0;
            $status = 0;
            $errPass = 0;
            $passInv = 0;
            $passMatchError = 0;
            //Función consultaPass definida en conexionbd.php
            $pass = consultaPass($connDB, $_SESSION['logUsuario']);
                
            $tuPass = password_verify($_POST['newPass'], $pass);
            
        //Si se verifica la contraseña de la cuenta ingresada y la contraseña nueva tiene entre 8 y 24 caracteres.
        if((password_verify($_POST['passNewPass'], $pass) && strlen($_POST['newPass']) >= 8 && strlen($_REQUEST['newPass']) <=24)){
                $newPass = password_hash($_POST['newPass'], PASSWORD_DEFAULT);   
                //Si la contraseña nueva es la misma que la actual.
                if(password_verify($_POST['newPass'], $pass)){
                    $tuPass = 1;
                }
                if(!$tuPass){
                    $newPassEnc = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
                    $sql = "UPDATE usuario SET pass='$newPassEnc' WHERE nomb_usu='{$_SESSION['logUsuario']}'";
                    if($connDB->query($sql) === TRUE){
                        $status = 1;
                    }else{
                        $status = 0;
                    }
                }
        //Si la contraseña actual ingresada es diferente a la contraseña de la cuenta.
        }else if(password_verify($_POST['passNewPass'], $pass)==0){
                    $errPass=1;
        }
            else if(strlen($_POST['newPass'])>24 || strlen($_POST['newPass'])<8){
                    $passInv = 1;
                }
        }else{
            $passMatchError = 1;
        
    }
        $rsp = array(
            "tuPass" => $tuPass,
            "status" => $status,
            "errPass" => $errPass,
            "passInv" => $passInv,
            "passMatch" => $passMatchError
        );
        echo json_encode($rsp);   
}

//Suspensión de cuentas----------------------------------------------------------------------------------------
if(isset($_POST['suspenderCuenta'])){
    $vacio = 0;
    $boolSusp = 0;
    $status = 0;
    if(isset($_POST['nombreSusp']) && $_POST['nombreSusp']!="" && $_POST['nombreSusp']!=null){
        if(isset($_POST['suspenderAction']) && $_POST['suspenderAction']=="suspender"  || isset($_POST['suspenderAction']) && $_POST['suspenderAction']=="quitarSusp"){
            if($_POST['suspenderAction']=="suspender"){$boolSusp=1;}
            if($_POST['suspenderAction']=="quitarSusp"){$boolSusp=0;}
            $sql = "UPDATE usuario SET suspendido='$boolSusp' WHERE nomb_usu='{$_POST['nombreSusp']}'";
            try{
                if($connDB->query($sql) === TRUE){
                    $status = 1;
                }
                else{
                    $status = 0;
                }
            }
            catch(Exception $e){
                $status = 0;
            }
        }
    }
    else{
        $vacio = 1;
    }
    if(!getUser($connDB, $_POST['nombreSusp'])){
        $status = 0;
    }
    $rsp = array(
        "vacio" => $vacio,
        "status" => $status
    );
    echo json_encode($rsp);
}

//Autosuspender cuenta
if(isset($_POST['autosuspender'])){
    session_start();
    $errPass = 0;
    $status = 0;
    $passMatchError = 0;
    $user = $_SESSION['logUsuario'];
    if($_POST['suspPass'] == $_POST['suspPass2']){
        if(password_verify($_POST['suspPass'], consultaPass($connDB, $user))){
            $sql = "UPDATE usuario SET suspendido='1' WHERE nomb_usu='$user'";
            try{
                if($connDB->query($sql)){
                    $status = 1;
                }else{
                    $status = 0;
                }
            }
            catch(Exception $e){
                echo "ERROR CON LA BASE DE DATOS";
            }
        }
        else{
            $errPass = 1;
        }
    }
    else{
        $passMatchError = 1;
    }
    $rsp = array(
        "errPass" => $errPass,
        "status" => $status,
        "passMatch" => $passMatchError
    );
    echo json_encode($rsp);
}

//Buscar Usuario
if(isset($_POST['buscarUsuario'])){
    if(isset($_POST['usuario']) && $_POST['usuario']!=null){
        $status = 0;
        $info = getUser($connDB, $_POST['usuario']);
        $tipo = 0;
        if($info==null || $info=="ERROR"){
            $status = 0;
        }
        else{
            $status = 1;
            if($info[0]["tipo"]=="cliente"){
                $tipo = 0;
            }
            if($info[0]["tipo"]=="empleado"){
                $tipo = 1;
            }
            if($info[0]["tipo"]=="adm"){
                $tipo = 2;
            }
        }

        if($status==1){
            $rsp = array(
                "status" => $status,
                "telNum" => $info[0]["num"],
                "tipo" => $tipo,
                "correo" => $info[0]["correo"],
                "ciudad" => $info[0]["ciudad"],
                "calle1" => $info[0]["calle1"],
                "calle2" => $info[0]["calle2"],
                "calle3" => $info[0]["calle3"],
                "nro" => $info[0]["nro"],
                "pass" => $info[0]["pass"],
            );
            echo json_encode($rsp);
        }
        else if($status == 0){
            $rsp = array(
                "status" => $status,
            );
            echo json_encode($rsp); 
        }
    }else{
        $rsp = array(
            "status" => 0,
        );
        echo json_encode($rsp); 
    }
}

if(isset($_POST['modificarUsuario'])){
    $GLOBALS['vacio'] = 0;
    $GLOBALS['tuEmail'] = 0;
    $GLOBALS['tuTel'] = 0;
    $GLOBALS['tuCiudad'] = 0;
    $GLOBALS['tuCalle1'] = 0;
    $GLOBALS['tuCalle2'] = 0;
    $GLOBALS['tuCalle3'] = 0;
    $GLOBALS['tuNro'] = 0;
    $GLOBALS['errTipo'] = 0;
    $GLOBALS['tipoCmp'] = 0;
    
    $error = 1;
    //Variables de retorno de error.
    $tuInfo = 0;
    $passwordSpaces = 0;
    $passwordlength = 0;
    $userpass = 0;
    $arrayUser = getUser($connDB, $_POST['usuario']);
    if(isset($arrayUser[0]['nomb_usu']) && isset($arrayUser[0]['pass']) && isset($arrayUser[0]['tipo']) && isset($arrayUser[0]['correo']) && isset($arrayUser[0]['nombre']) && isset($arrayUser[0]['apellido']) && isset($arrayUser[0]['ci']) && isset($arrayUser[0]['num']) && isset($arrayUser[0]['fecha_nac'])){
        $usuario = new Usuario ($arrayUser[0]['nomb_usu'], $arrayUser[0]['pass'], $arrayUser[0]['tipo'], $arrayUser[0]['correo'], $arrayUser[0]['nombre'], $arrayUser[0]['apellido'], $arrayUser[0]['ci'], $arrayUser[0]['num'], $arrayUser[0]['fecha_nac']);
        $error = 0;
    }
    if($error==0){
        if(isset($_POST['ciudad'])){
            $usuario->setCiudad($_POST['ciudad']);
        }
        if(isset($_POST['calle1'])){
            $usuario->setCalle1($_POST['calle1']);
        }
        if(isset($_POST['calle2'])){
            $usuario->setCalle2($_POST['calle2']);
        }
        if(isset($_POST['calle3'])){
            $usuario->setCalle3($_POST['calle3']);
        }
        if(isset($_POST['nro'])){
            $usuario->setNro($_POST['nro']);
        }
        if(isset($_POST['emailMod'])){
            $usuario->setEmail($_POST['emailMod']);
        }
        if(isset($_POST['telNum'])){
            $usuario->setTel($_POST['telNum']);
        }
        if($_POST['emailMod'] == $usuario->getEmail()){
            $GLOBALS['tuEmail'] = $_POST['emailMod'];
        }
        if($_POST['telNum'] == $usuario->getTel()){
            $GLOBALS['tuTel'] = 1;
        }
        if($_POST['ciudad'] == $arrayUser[0]['ciudad']){
            $GLOBALS['tuCiudad'] = 1;
        }
        if($_POST['calle1'] == $arrayUser[0]['calle1']){
            $GLOBALS['tuCalle1'] = 1;
        }
        if($_POST['calle2'] == $arrayUser[0]['calle2']){
            $GLOBALS['tuCalle2'] = 1;
        }
        if($_POST['calle3'] == $arrayUser[0]['calle3']){
            $GLOBALS['tuCalle3'] = 1;
        }
        if($_POST['nro'] == $arrayUser[0]['nro']){
            $GLOBALS['tuNro'] = 1;
        }
        if(isset($_POST['selectTipo'])){
            if($_POST['selectTipo'] != 0 && $_POST['selectTipo'] != 1 && $_POST['selectTipo'] != 2){
                $GLOBALS['errTipo'] = 1;
            }
    
            if($_POST['selectTipo']==0){
                $tipo = "cliente";
            }
            else if ($_POST['selectTipo']==1){
                $tipo = "empleado";
            }
            else if ($_POST['selectTipo']==2){
                $tipo = "adm";
            }
            if($tipo == $arrayUser[0]["tipo"]){
                $GLOBALS['tipoCmp'] = 1;
            }
        }
        else{
            $GLOBALS['errTipo']=1;
        }
    
        if(isset($_POST['newPass'])){
            $pass = $_POST['newPass'];
            $tuInfo = 0;
            $passwordSpaces = 0;
            $passwordlength = 0;
            $userpass = 0;
            $pass = trim($pass);
            if($_POST['newPass'] == $arrayUser[0]['pass']){
                $GLOBALS['tuPass'] = 1;
            }
    
            if(strlen($pass) < 8 || strlen($pass) > 24){
                $passwordlength = 1;
            }
            if($pass == $_POST['usuario']){
                $userpass = 1;
            }
            if(strpos($pass, ' ')){
                $passwordSpaces = 1;  
            }
            if($passwordlength!=1 && $userpass!=1 && $passwordSpaces!=1){
            $passH=password_hash($pass, PASSWORD_DEFAULT);
            }
            else{
                $passH=null;
            }
        }
        else{
            $passH = null;
        }
        if($pass==null){
            $passwordlength = 0;
            $userpass = 0;
            $passwordSpaces = 0;
        }
        if(strlen($_POST['newPass'])>0){
            if($arrayUser[0]['num']==$_POST['telNum'] && password_verify($_POST['newPass'], $arrayUser[0]['pass']) && $arrayUser[0]['correo'] == $_POST['emailMod'] && $tipo == $arrayUser[0]['tipo'] && $arrayUser[0]['ciudad'] == $_POST['ciudad'] && $arrayUser[0]['calle1'] == $_POST['calle1'] && $arrayUser[0]['calle2'] == $_POST['calle2'] && $arrayUser[0]['calle3'] == $_POST['calle3'] && $arrayUser[0]['nro'] == $_POST['nro']){
                $tuInfo = 1;
            }
        }
        else{
            if($arrayUser[0]['num']==$_POST['telNum'] && $arrayUser[0]['correo'] == $_POST['emailMod'] && $tipo == $arrayUser[0]['tipo'] && $arrayUser[0]['ciudad'] == $_POST['ciudad'] && $arrayUser[0]['calle1'] == $_POST['calle1'] && $arrayUser[0]['calle2'] == $_POST['calle2'] && $arrayUser[0]['calle3'] == $_POST['calle3'] && $arrayUser[0]['nro'] == $_POST['nro']){
                $tuInfo = 1;
            } 
        }
        //Método updateUsuarioBd definido en la clase Usuario.
        $usuario->updateUsuarioBd($arrayUser[0]['ci'], $_POST['emailMod'], $_POST['telNum'], $_POST['ciudad'], $_POST['calle1'], $_POST['calle2'], $_POST['calle3'], $_POST['nro'], $tipo, $passH, 2/*Tipos:1 mi cuenta, 2 panel adm*/);
    
    }
    else{
        $GLOBALS['updateerror']=1;
    }
    
    $rsp = array(
        'vacio' => $GLOBALS['vacio'],
        'prohibidos' => $GLOBALS['prohibidos'],
        'email' => $GLOBALS['email'],
        'existentecorreo' => $GLOBALS['updExistenteCorreo'],
        'celularexistente' => $GLOBALS['updExistenteTel'],
        'tellength' => $GLOBALS['tellength'],
        'tel' => $GLOBALS['tel'],
        'ciudadlength' => $GLOBALS['ciudadlength'],
        'calle1length' => $GLOBALS['calle1length'],
        'calle2length' => $GLOBALS['calle2length'],
        'calle3length' => $GLOBALS['calle3length'],
        'nrolength' => $GLOBALS['nrolength'],
        'updateerror' => $GLOBALS['updateerror'],
        'passwordlength' => $passwordlength,
        'userpass' => $userpass,
        'passwordSpaces' => $passwordSpaces,
        'tuInfo' => $tuInfo,
    );
    echo json_encode($rsp);
}
    //Eliminar un elemento del historial.
    if(isset($_POST['removerHistorial']) && isset($_POST['idRemover'])){
        session_start();
        //Función removeHistoryElement definida en conexionbd.php
        if(removeHistoryElement($connDB, $_SESSION['logUsuario'], $_POST['idRemover'])){
            echo 1;
        }
        else{
            echo 0;
        }
    }
?>