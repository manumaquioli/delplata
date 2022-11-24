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
//-------------------------------------------------------------------------------------------------------------------------------------------------
    //Login------------------------------------------------------------------------------------------------------------------------------------
            $loginStatus = 0;
            $loginVacio = 0;
            //Variables información del usuario
            $loginUser = 0;
            $loginTipo = 0;
            $susp = 0;

        //Verificación de posibilidad de campos vacíos y verifica que estén declarados.
    if(isset($_REQUEST['loginUsername']) && isset($_REQUEST['loginPassword'])){
        if( !empty($_REQUEST['loginUsername']) && !empty($_REQUEST['loginPassword']) ){
            $loginUser=$_REQUEST['loginUsername'];
                //Si no hay campos vacíos:
                try{
                    //Asigna a la variable user el string limpio de lo que el usuario ingresa en el campo de usuario.
                    $user = mysqli_real_escape_string($connDB, $_REQUEST["loginUsername"]);
                    //Asigna a la variable pass el string limpio de lo que el usuario ingresa en el campo de pass.
                    $pass = mysqli_real_escape_string($connDB, $_REQUEST["loginPassword"]);

                    $allUsuario = mysqli_query($connDB, "SELECT * FROM usuario");
                    $arrayDatos = array();

                    while($fila = mysqli_fetch_array($allUsuario)){
                        $arrayDatos[] = $fila;
                    }
                }
                catch(Exception $e){
                    echo "Error con la base de datos.";
                }
                //Se recorre el arreglo de los usuarios
                foreach($arrayDatos as $valor){
                    if($user==$valor['nomb_usu'] && (password_verify($pass, $valor['pass']) || $pass == $valor['pass']) ){
                        //Se asigna 1 a la variable loginStatus.
                        $loginStatus=1;
                        
                        $loginTipo = $valor['tipo'];
                        $loginUser = $valor['nomb_usu'];
                        break;
                    }
                }
            }
            // Si no se ingresa nada en usuario o contraseña:
            else{
                //Se asigna el valor 1 a la variable loginVacio.
                $loginVacio=1;
            }
            //------------
            if($loginUser!=null && $loginUser!="" && $loginUser!=0 && strlen($loginUser) >= 5){
                if(comprobar_susp($connDB, $loginUser)==1){
                    $loginStatus=0;
                    $susp = 1;
                }
            }
            if($loginStatus==1){
                session_start();
                //Guarda en la sesión actual, el nombre de usuario logeado y su tipo.
                $_SESSION['logUsuario'] = $loginUser;
                $_SESSION['logTipo'] = $loginTipo;
            }
            $url="";
            if(isset($loginTipo)){
                if($loginTipo=="cliente"){
                    $url = "../../index.php";
                }
                else if($loginTipo=="empleado"){
                    $url = "panel_emp.php";
                }
                else if($loginTipo=="adm"){
                    $url = "panel_adm.php";
                }
            }
            $rsp = array('vacio' => $loginVacio, 'status' => $loginStatus, 'tipo' => $url, 'user' => $loginUser, 'susp' => $susp);
                $resp = json_encode($rsp);
                echo $resp;
    }else{
        header("location:../../enlaces/login.php");
    }
?>

